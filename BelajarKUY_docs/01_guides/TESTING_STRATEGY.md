# 🧪 BelajarKUY — Testing Strategy

> Panduan testing untuk project BelajarKUY.
> Target: minimum **happy-path Feature test** per major controller di MVP.

---

## Testing Pyramid

```
           ▲
          / \
         /   \  Browser (Dusk) — End-to-end, slow, optional
        /─────\
       /       \ Feature Tests — HTTP level, medium speed (PRIMARY FOCUS)
      /─────────\
     /           \ Unit Tests — Class-level, fast
    /─────────────\
```

**Priority untuk MVP akademik:**
1. **Feature tests** — Login, register, checkout, course CRUD (happy paths)
2. **Unit tests** — Model accessors (`discountedPrice`), scopes (`Coupon::active()`)
3. **Browser tests** — Optional, hanya untuk flow checkout end-to-end

---

## Folder Structure

```
tests/
├── Feature/
│   ├── Auth/
│   │   ├── RegistrationTest.php
│   │   ├── LoginTest.php
│   │   └── PasswordResetTest.php
│   ├── Course/
│   │   ├── InstructorCourseCrudTest.php
│   │   └── StudentCourseBrowseTest.php
│   ├── Commerce/
│   │   ├── CartTest.php
│   │   ├── WishlistTest.php
│   │   ├── CheckoutTest.php
│   │   └── CouponApplyTest.php
│   ├── Player/
│   │   └── LectureCompletionTest.php
│   └── Admin/
│       ├── CategoryCrudTest.php
│       └── CourseApprovalTest.php
├── Unit/
│   ├── Models/
│   │   ├── CourseTest.php
│   │   ├── CouponTest.php
│   │   └── UserTest.php
│   └── Services/
│       └── MidtransServiceTest.php
└── Pest.php / TestCase.php
```

---

## Framework

**Pest** (default Laravel 12) — modern, expressive syntax.

Jika tim belum familiar Pest, PHPUnit classic juga OK. Jangan mix — pilih satu.

---

## Test Template: Feature Test

```php
// tests/Feature/Commerce/CartTest.php
<?php

use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

beforeEach(function () {
    $this->student = User::factory()->student()->create();
    $this->instructor = User::factory()->instructor()->create();
    $this->course = Course::factory()->active()->create([
        'instructor_id' => $this->instructor->id,
    ]);
});

test('authenticated student can add course to cart', function () {
    $response = $this->actingAs($this->student)
        ->postJson('/cart/add', ['course_id' => $this->course->id]);

    $response->assertOk()
        ->assertJson(['success' => true, 'cart_count' => 1]);

    $this->assertDatabaseHas('carts', [
        'user_id' => $this->student->id,
        'course_id' => $this->course->id,
    ]);
});

test('cannot add course if already enrolled', function () {
    Enrollment::factory()->create([
        'user_id' => $this->student->id,
        'course_id' => $this->course->id,
    ]);

    $response = $this->actingAs($this->student)
        ->postJson('/cart/add', ['course_id' => $this->course->id]);

    $response->assertStatus(409)
        ->assertJson(['message' => 'Kamu sudah memiliki kursus ini.']);
});

test('adding duplicate is idempotent', function () {
    $this->actingAs($this->student)
        ->postJson('/cart/add', ['course_id' => $this->course->id])
        ->assertOk();

    $this->actingAs($this->student)
        ->postJson('/cart/add', ['course_id' => $this->course->id])
        ->assertOk();

    expect(Cart::count())->toBe(1);
});

test('guest cannot add to cart', function () {
    $this->postJson('/cart/add', ['course_id' => $this->course->id])
        ->assertStatus(401);
});
```

---

## Test Template: Unit Test (Model)

```php
// tests/Unit/Models/CouponTest.php
<?php

use App\Models\Coupon;

test('scope active returns only valid coupons', function () {
    $valid = Coupon::factory()->create([
        'status' => true,
        'valid_until' => now()->addDays(7),
        'max_usage' => 100,
        'used_count' => 10,
    ]);
    $expired = Coupon::factory()->create([
        'status' => true,
        'valid_until' => now()->subDay(),
    ]);
    $disabled = Coupon::factory()->create(['status' => false]);
    $exhausted = Coupon::factory()->create([
        'status' => true,
        'max_usage' => 10,
        'used_count' => 10,
    ]);

    $active = Coupon::active()->pluck('id');

    expect($active)->toContain($valid->id);
    expect($active)->not->toContain($expired->id);
    expect($active)->not->toContain($disabled->id);
    expect($active)->not->toContain($exhausted->id);
});

test('unlimited coupon (max_usage null) is always available', function () {
    $coupon = Coupon::factory()->create([
        'status' => true,
        'valid_until' => now()->addDays(7),
        'max_usage' => null,
        'used_count' => 99999,
    ]);

    expect(Coupon::active()->find($coupon->id))->not->toBeNull();
});
```

---

## Testing Midtrans (Mocking)

**Jangan** pernah call Midtrans API beneran di test suite. Mock it:

```php
// tests/Feature/Commerce/CheckoutTest.php
use App\Services\MidtransService;

test('checkout creates pending payment and snap token', function () {
    // Mock MidtransService
    $this->mock(MidtransService::class, function ($mock) {
        $mock->shouldReceive('createSnapToken')
            ->once()
            ->andReturn('mock-snap-token-abc123');
    });

    // Setup cart
    $student = User::factory()->student()->create();
    $course = Course::factory()->active()->create(['price' => 100000]);
    Cart::create(['user_id' => $student->id, 'course_id' => $course->id]);

    // Act
    $response = $this->actingAs($student)
        ->post('/checkout/process');

    // Assert
    $response->assertOk()
        ->assertViewHas('snapToken', 'mock-snap-token-abc123');

    $this->assertDatabaseHas('payments', [
        'user_id' => $student->id,
        'status' => 'pending',
        'total_amount' => 100000,
    ]);
});
```

---

## Testing Midtrans Webhook (Faked Callback)

```php
test('webhook settlement creates order and enrollment', function () {
    $student = User::factory()->student()->create();
    $course = Course::factory()->active()->create();
    $payment = Payment::factory()->pending()->create([
        'user_id' => $student->id,
        'midtrans_order_id' => 'BKUY-123',
        'total_amount' => 100000,
    ]);
    Cart::create(['user_id' => $student->id, 'course_id' => $course->id]);

    // Mock Notification parsing
    $this->mock(MidtransService::class, function ($mock) use ($payment) {
        $notif = (object) [
            'order_id' => $payment->midtrans_order_id,
            'transaction_status' => 'settlement',
            'fraud_status' => null,
            'payment_type' => 'gopay',
            'transaction_id' => 'midtrans-tx-123',
        ];
        $mock->shouldReceive('handleNotification')->andReturn($notif);
    });

    // Act
    $response = $this->postJson('/payment/callback');

    // Assert
    $response->assertOk();
    expect($payment->fresh()->status)->toBe('settlement');
    $this->assertDatabaseHas('orders', [
        'payment_id' => $payment->id,
        'status' => 'completed',
    ]);
    $this->assertDatabaseHas('enrollments', [
        'user_id' => $student->id,
        'course_id' => $course->id,
    ]);
    $this->assertDatabaseMissing('carts', [
        'user_id' => $student->id,
    ]);
});
```

---

## Running Tests

```bash
# All tests
php artisan test

# Specific file
php artisan test tests/Feature/Commerce/CartTest.php

# With coverage (butuh Xdebug/PCOV)
php artisan test --coverage --min=60
```

---

## Coverage Target

| Layer | Target MVP | Target Production |
|-------|------------|-------------------|
| Feature tests | 5 major flows | All controllers |
| Unit tests | Model accessors + scopes | 80%+ |
| Total | 30% | 60%+ |

Jangan obsessed dengan 100% coverage. Cover **happy paths + 1-2 edge cases** per controller.

---

## Database di Test

Gunakan `RefreshDatabase` trait. Default pakai SQLite in-memory (cepat):

```php
// phpunit.xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

---

## Test Data via Factories

Semua 19 model sudah punya factory — pakai itu, jangan manual `create()`:

```php
// ✅ BENAR
$course = Course::factory()->featured()->create();
$coupon = Coupon::factory()->expired()->create();

// ❌ SALAH — redundant
$course = Course::create([
    'title' => 'Test', 'slug' => 'test', 'category_id' => 1, ...
]);
```

---

## Continuous Integration (Optional)

Untuk project yang akan lanjut commercially, setup GitHub Actions:

```yaml
# .github/workflows/test.yml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
      - run: composer install --no-progress
      - run: php artisan test
```

**Not required untuk project akademik.**

---

## Testing Checklist per Feature (Definition of Done)

Sebuah feature dianggap "done" jika:

- [ ] Happy path Feature test lulus
- [ ] 1+ authorization test (role check, ownership check)
- [ ] 1+ validation test (form validation error)
- [ ] Model scope/accessor/relationship yang baru ada Unit test
- [ ] Manual smoke test: login, jalankan flow, lihat hasil

---

*Testing adalah investment. Sedikit di awal, banyak di akhir saat ada bug.*
