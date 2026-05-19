# 🚀 Modern Tech Stack — Adopted for BelajarKUY

> Teknologi tambahan yang **SUDAH DIADOPSI** ke dalam arsitektur BelajarKUY.
> Status: ✅ **ADOPTED** — Bukan lagi rekomendasi, melainkan bagian dari tech stack resmi.

---

## ✅ 1. Alpine.js — Frontend Interactivity

**Status:** ADOPTED (sudah terinstall via `npm install alpinejs`)

Alpine.js digunakan sebagai "Tailwind-nya JavaScript" — membuat UI interaktif langsung di Blade tanpa file `.js` terpisah.

### Penggunaan di BelajarKUY:
- **Dropdown** menu (navbar, user menu)
- **Modal** dialogs (confirm delete, preview)
- **Tabs** (course detail: Deskripsi, Kurikulum, Review)
- **Accordion** (course sections → lectures)
- **Toggle** (sidebar collapse, status toggle)
- **Search bar** live filtering
- **Slider/Carousel** (hero section)

### Setup di `resources/js/app.js`:
```javascript
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

### Contoh Usage di Blade:
```blade
{{-- Dropdown --}}
<div x-data="{ open: false }">
    <button @click="open = !open">Menu</button>
    <div x-show="open" @click.away="open = false" x-transition>
        <!-- dropdown content -->
    </div>
</div>

{{-- Accordion --}}
<div x-data="{ expanded: false }">
    <button @click="expanded = !expanded">Section 1</button>
    <div x-show="expanded" x-collapse>
        <!-- lectures list -->
    </div>
</div>
```

---

## ✅ 2. Cloudinary + YouTube — Media Storage

**Status:** ADOPTED

### Cloudinary (Gambar & Thumbnail)
- **Package:** `cloudinary-labs/cloudinary-laravel`
- **Fungsi:** Upload, resize, dan optimasi gambar otomatis
- **Digunakan untuk:** Thumbnail kursus, foto profil, gambar kategori, slider, partner logos
- **Budget:** Free tier 25 Credits/bulan — sangat cukup untuk demo

### YouTube Unlisted (Video Kursus — Backup)
- **Fungsi:** Hosting video materi kursus
- **Cara kerja:** Upload video ke YouTube sebagai Unlisted → simpan URL di `course_lectures.url` → embed iframe di halaman lecture
- **Budget:** 100% Gratis
- **Fallback:** Jika Cloudinary video tidak memadai, gunakan YouTube sebagai backup utama

### Environment Variables:
```env
# Cloudinary
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
CLOUDINARY_UPLOAD_PRESET=belajarkuy_unsigned
```

### Config File (`config/cloudinary.php`):
> Auto-generated oleh package `cloudinary-labs/cloudinary-laravel`.

### Upload Pattern:
```php
// Upload gambar ke Cloudinary
$result = $request->file('thumbnail')->storeOnCloudinary('belajarkuy/courses');
$url = $result->getSecurePath();
$publicId = $result->getPublicId();

// Simpan ke database
$course->update([
    'thumbnail' => $url,
]);
```

### Video Embed Pattern (YouTube):
```blade
{{-- Di lecture view --}}
@if(Str::contains($lecture->url, 'youtube'))
    <iframe src="{{ $lecture->url }}" 
            class="w-full aspect-video rounded-lg"
            allowfullscreen></iframe>
@endif
```

---

## ✅ 3. Email Transactional — Resend + Mailtrap

**Status:** ADOPTED

### Resend.com (Production / Demo)
- **Package:** `resend/resend-laravel` (atau gunakan SMTP driver bawaan Laravel)
- **Fungsi:** Kirim email beneran saat register, lupa password, pembelian kursus
- **Budget:** Gratis 3.000 email/bulan (maks 100/hari)
- **Digunakan untuk:** Welcome email, reset password, konfirmasi order, notif enrollment

### Mailtrap (Local Development / Testing)
- **Fungsi:** Tangkap email di lokal tanpa kirim ke inbox asli
- **Budget:** Gratis
- **Digunakan saat:** Development & testing di localhost

### Environment Variables:
```env
# === PRODUCTION (Resend) ===
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=465
MAIL_USERNAME=resend
MAIL_PASSWORD=re_xxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@belajarkuy.my.id
MAIL_FROM_NAME=BelajarKUY

# === LOCAL DEV (Mailtrap) — uncomment saat development ===
# MAIL_MAILER=smtp
# MAIL_HOST=sandbox.smtp.mailtrap.io
# MAIL_PORT=2525
# MAIL_USERNAME=
# MAIL_PASSWORD=
# MAIL_FROM_ADDRESS=noreply@belajarkuy.test
# MAIL_FROM_NAME=BelajarKUY
```

### Email Notifications yang Harus Dibuat:
| Event | Recipient | Template |
|-------|-----------|----------|
| User mendaftar | User | `WelcomeEmail` |
| Lupa password | User | Built-in Breeze |
| Pembelian berhasil | User (buyer) | `OrderConfirmationEmail` |
| Ada penjualan baru | Instructor | `NewSaleNotification` |
| Kursus disetujui admin | Instructor | `CourseApprovedEmail` |
| Kursus ditolak admin | Instructor | `CourseRejectedEmail` |

### Laravel Mail Class Pattern:
```php
// app/Mail/OrderConfirmationMail.php
class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembelian — BelajarKUY',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }
}
```

---

## ✅ 4. Meilisearch + Laravel Scout — Fast Search

**Status:** ADOPTED

### Meilisearch (Search Engine)
- **Package:** `laravel/scout` + `meilisearch/meilisearch-php`
- **Fungsi:** Pencarian kursus super cepat, typo-tolerant, keystroke search
- **Budget:** 100% Gratis (self-host), atau gunakan Meilisearch Cloud free tier
- **Digunakan untuk:** Cari kursus berdasarkan title, description, category, instructor name

### Environment Variables:
```env
# Meilisearch
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=masterKey
```

### Config (`config/scout.php`):
```php
'meilisearch' => [
    'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
    'key' => env('MEILISEARCH_KEY', null),
    'index-settings' => [
        Course::class => [
            'filterableAttributes' => ['status', 'category_id', 'subcategory_id', 'bestseller', 'featured'],
            'sortableAttributes' => ['price', 'created_at', 'title'],
            'searchableAttributes' => ['title', 'description'],
        ],
    ],
],
```

### Model Setup:
```php
// app/Models/Course.php
use Laravel\Scout\Searchable;

class Course extends Model
{
    use HasFactory, Searchable;

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'bestseller' => $this->bestseller,
            'featured' => $this->featured,
            'instructor_name' => $this->instructor?->name,
            'category_name' => $this->category?->name,
        ];
    }
}
```

### Search Controller:
```php
// app/Http/Controllers/Frontend/SearchController.php
class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $courses = Course::search($query)
            ->where('status', 'active')
            ->paginate(12);

        return view('frontend.search', compact('courses', 'query'));
    }

    // API endpoint untuk live search (Alpine.js)
    public function liveSearch(Request $request)
    {
        $courses = Course::search($request->get('q', ''))
            ->where('status', 'active')
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'title' => $c->title,
                'slug' => $c->slug,
                'thumbnail' => $c->thumbnail,
                'price' => $c->discounted_price,
            ]);

        return response()->json($courses);
    }
}
```

### Routes:
```php
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/api/search', [SearchController::class, 'liveSearch'])->name('search.live');
```

### Frontend Live Search (Alpine.js):
```blade
<div x-data="searchComponent()" class="relative">
    <input type="text" x-model="query" @input.debounce.300ms="search()"
           placeholder="Cari kursus..." class="...">
    
    <div x-show="results.length > 0" class="absolute bg-white shadow-lg rounded-lg mt-1 w-full z-50">
        <template x-for="course in results" :key="course.slug">
            <a :href="'/course/' + course.slug" class="flex items-center p-3 hover:bg-gray-50">
                <img :src="course.thumbnail" class="w-12 h-12 rounded">
                <div class="ml-3">
                    <p x-text="course.title" class="font-medium"></p>
                    <p x-text="'Rp ' + course.price.toLocaleString('id-ID')" class="text-sm text-gray-500"></p>
                </div>
            </a>
        </template>
    </div>
</div>

<script>
function searchComponent() {
    return {
        query: '',
        results: [],
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            const res = await fetch(`/api/search?q=${this.query}`);
            this.results = await res.json();
        }
    }
}
</script>
```

---

## ✅ 5. Laravel Reverb — Real-time Notifications

**Status:** ADOPTED (menggunakan Pusher sebagai fallback jika setup Reverb terlalu kompleks)

### Laravel Reverb (Primary)
- **Package:** Built-in Laravel 12 (`php artisan install:broadcasting`)
- **Fungsi:** WebSocket server untuk notifikasi real-time
- **Budget:** 100% Gratis (self-hosted)
- **Digunakan untuk:**
  - Notifikasi pembayaran berhasil (auto-update halaman tanpa refresh)
  - Notif kursus baru untuk student
  - Notif penjualan baru untuk instructor
  - Notif review baru untuk instructor

### Pusher (Fallback — jika tidak mau setup Reverb server)
- **Budget:** Free tier 200k messages/day
- **Lebih mudah setup:** Hanya perlu API key, tidak perlu server sendiri

### Environment Variables:
```env
# === REVERB (Primary) ===
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=belajarkuy
REVERB_APP_KEY=belajarkuy-key
REVERB_APP_SECRET=belajarkuy-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# === PUSHER (Fallback) ===
# BROADCAST_CONNECTION=pusher
# PUSHER_APP_ID=
# PUSHER_APP_KEY=
# PUSHER_APP_SECRET=
# PUSHER_APP_CLUSTER=ap1
```

### Event: PaymentSuccessful
```php
// app/Events/PaymentSuccessful.php
class PaymentSuccessful implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Payment $payment,
        public string $message = 'Pembayaran berhasil!'
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->payment->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.success';
    }
}
```

### Dispatch Event (di handleSuccess):
```php
// Di CheckoutController::handleSuccess()
private function handleSuccess(Payment $payment): void
{
    $payment->update(['status' => 'settlement']);
    // ... create orders, enrollments ...

    // Broadcast real-time notification
    event(new PaymentSuccessful($payment));
    
    // Kirim email konfirmasi
    Mail::to($payment->user)->send(new OrderConfirmationMail($payment));
}
```

### Frontend Listener (Echo + Alpine.js):
```javascript
// resources/js/app.js
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

```blade
{{-- Di layout utama — toast notification component --}}
<div x-data="notificationListener()" x-init="listen()">
    <template x-if="notification">
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
             x-transition x-show="showNotif" @click="showNotif = false">
            <p x-text="notification"></p>
        </div>
    </template>
</div>

<script>
function notificationListener() {
    return {
        notification: null,
        showNotif: false,
        listen() {
            if (window.Echo && window.userId) {
                window.Echo.private(`user.${window.userId}`)
                    .listen('.payment.success', (e) => {
                        this.notification = e.message;
                        this.showNotif = true;
                        setTimeout(() => this.showNotif = false, 5000);
                    });
            }
        }
    }
}
</script>
```

---

## 📦 Summary: Package Tambahan yang Harus Di-install

### Composer:
```bash
composer require cloudinary-labs/cloudinary-laravel
composer require laravel/scout
composer require meilisearch/meilisearch-php
```

### NPM:
```bash
npm install laravel-echo pusher-js
```

### Artisan:
```bash
php artisan install:broadcasting  # Setup Reverb
php artisan scout:import "App\Models\Course"  # Index courses ke Meilisearch
```

---

## 🎯 Tech Stack CV-ready

> **BelajarKUY (LMS Platform)**
> *Tech Stack: Laravel 12, MySQL, TailwindCSS v4, Alpine.js, Midtrans Snap (Payment), Cloudinary (Media), Meilisearch + Laravel Scout (Search), Laravel Reverb (WebSocket), Resend (Email), YouTube (Video Hosting).*

**Zero cost (Rp 0)** — semua service di atas punya free tier yang cukup untuk project akademik.
