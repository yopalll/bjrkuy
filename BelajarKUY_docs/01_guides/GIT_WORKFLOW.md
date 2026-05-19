# 🌿 BelajarKUY — Git Workflow

> Branching strategy sederhana untuk tim kecil (5 orang) dengan cycle 4 minggu.
> **Version:** 2.0 — Simplified (no `develop` branch)

---

## Branch Strategy

```
main (protected, always deployable)
 ├── feature/auth-system
 ├── feature/course-management
 ├── feature/payment-midtrans
 ├── feature/cart-wishlist
 ├── feature/landing-page
 ├── feature/student-dashboard
 ├── feature/admin-panel
 ├── feature/review-system
 ├── feature/database-migrations    ← sudah merged
 ├── feature/database-seeders       ← sudah merged
 ├── docs/audit-cleanup              ← docs branch
 ├── hotfix/xxx
 └── chore/xxx
```

### Branch Naming

| Prefix | Untuk |
|--------|-------|
| `feature/*` | Fitur baru atau enhancement |
| `fix/*` | Bug fix non-critical |
| `hotfix/*` | Bug kritikal production |
| `docs/*` | Perubahan dokumentasi only |
| `chore/*` | Refactor, config, dependency bump |

### Branch Rules

| Branch | Siapa yang merge | Aturan |
|--------|-----------------|--------|
| `main` | PIC feature | Via Pull Request + 1 approval dari PM |
| `feature/*` | Author | Bebas push, PR ke `main` |
| `hotfix/*` | Author | Fast-track PR — 1 approval cukup |

---

## Commit Convention

Format: `<prefix>: <deskripsi singkat>` atau `[MODUL] <deskripsi>`

### Prefix (Conventional Commits style — recommended)

| Prefix | Untuk |
|--------|-------|
| `feat:` | Fitur baru |
| `fix:` | Bug fix |
| `docs:` | Dokumentasi |
| `refactor:` | Refactor tanpa ubah behavior |
| `style:` | Formatting, no code change |
| `test:` | Menambahkan/update tests |
| `chore:` | Config, tooling, deps |

### Contoh Commit Messages

```bash
git commit -m "feat(auth): implement role middleware + separate login pages"
git commit -m "feat(course): CRUD course dengan section & lecture"
git commit -m "feat(payment): integrate Midtrans Snap API"
git commit -m "fix(cart): hapus item setelah checkout sukses"
git commit -m "test(coupon): add coupon scope active test cases"
git commit -m "docs: update progress tracker sprint 2"
git commit -m "refactor(models): extract price calculation to accessor"
```

### Legacy Prefix (masih OK)

Jika lebih familiar dengan format lama, ini juga diterima:

```bash
git commit -m "[AUTH] Implementasi multi-role middleware"
git commit -m "[PAYMENT] Integrasi Midtrans Snap API"
```

**Consistent di satu repo lebih penting daripada style specific.**

---

## Workflow Harian

### Memulai Task Baru

```bash
# 1. Sync main
git checkout main
git pull origin main

# 2. Buat branch feature
git checkout -b feature/nama-fitur

# 3. Kerjakan...
# 4. Commit + push
git add .
git commit -m "feat(module): deskripsi"
git push -u origin feature/nama-fitur
```

### Menyelesaikan Task

```bash
# 1. Pastikan up-to-date dengan main
git checkout main
git pull origin main
git checkout feature/nama-fitur
git merge main   # atau git rebase main kalau lebih suka linear history

# 2. Resolve conflicts jika ada

# 3. Push
git push origin feature/nama-fitur

# 4. Buat Pull Request di GitHub/GitLab dengan:
#    - Title: feat(module): deskripsi
#    - Description: apa yang berubah, kenapa, cara test
#    - Reviewer: PM (Yosua)
```

---

## Pull Request Rules

### PR Title

Pakai Conventional Commit prefix:
- ✅ `feat(auth): implement Google OAuth`
- ✅ `fix(checkout): prevent double charge`
- ❌ `updated some files` — tidak informatif

### PR Description Template

```markdown
## What
[Deskripsi singkat perubahan]

## Why
[Alasan dibutuhkan / link ke issue/spec]

## How to Test
1. Checkout branch
2. php artisan migrate:fresh --seed
3. Login sebagai X
4. Klik Y → verifikasi Z

## Checklist
- [ ] Code berjalan tanpa error
- [ ] `php artisan migrate` sukses
- [ ] Tidak ada hardcoded credentials
- [ ] `PROGRESS_TRACKER.md` updated
- [ ] Feature test happy path added
- [ ] Tidak ada conflict dengan main
```

### Review Turnaround

- Max 24 jam untuk review (kecuali weekend)
- Jika blocked > 24 jam, ping PM di chat

---

## Resolving Conflicts

```bash
# 1. Update main
git checkout main
git pull origin main

# 2. Rebase atau merge ke branch fitur
git checkout feature/nama-fitur
git rebase main
# atau: git merge main

# 3. Resolve conflicts di editor
# 4. Stage & continue
git add .
git rebase --continue   # jika rebase
# atau: git commit -m "chore: resolve merge conflicts"

# 5. Force push (if rebased)
git push --force-with-lease origin feature/nama-fitur
```

---

## Initial Repository Setup (PM Only — sudah done)

```bash
# Ref only — sudah dilakukan
laravel new BelajarKUY --git
cd BelajarKUY
git remote add origin https://github.com/yopalll/BelajarKUY.git
git push -u origin main
```

---

## Git Hooks (Optional — Recommended)

Setup pre-commit hook untuk auto-format code:

```bash
# .git/hooks/pre-commit
#!/bin/sh
./vendor/bin/pint --test
```

---

## FAQ

### "Kenapa tidak ada branch `develop`?"

Tim kecil (5 orang) + project 4 minggu = tidak perlu overhead two-layer branching.
Direct feature → main sudah cukup dengan PR approval sebagai gate.

### "Kapan perlu hotfix branch?"

Jika bug terjadi di production demo dan butuh fix **sebelum** next feature merge.
Untuk project akademik yang belum deploy, semua jadi `fix/*` saja.

### "Siapa yang boleh push ke main?"

**Tidak ada.** Semua perubahan via PR. Protect branch `main` di GitHub settings:
- Require PR before merging
- Require at least 1 approval
- Disallow force push

---

*Keep it simple. Git workflow tidak harus kompleks untuk project akademik.*
