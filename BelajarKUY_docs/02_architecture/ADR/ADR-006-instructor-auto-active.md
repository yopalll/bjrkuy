# ADR-006: Instructor Langsung Aktif Saat Register (No Approval)

**Status:** ✅ Accepted
**Date:** 14 Mei 2026
**Decision By:** Yosua (PM) — default decision via audit recommendation

---

## Context

Dalam platform LMS komersial biasanya ada 2 model:
1. **Open registration** — siapa saja bisa register sebagai instructor, langsung aktif
2. **Curated marketplace** — register sebagai instructor butuh approval admin (KYC, portfolio review, dll)

`F07_ADMIN_PANEL.md` sebelumnya mention "Approve/Block instructor" tapi:
- Tidak ada field `is_approved` di `users`
- Tidak ada flow/UI/notification
- Spec tidak lengkap

## Decision

**Instructor langsung aktif saat register.** Tidak ada approval flow di MVP.

### Yang TETAP ADA

- **Course approval flow**: course dari instructor default status `draft`, instructor submit → jadi `pending_review`, admin approve → jadi `active`. Ini gatekeeper kualitas konten.
- **Soft moderation via course status**: admin bisa set course ke `inactive` kapan saja (efektif "unpublish" tanpa hapus)

### Yang TIDAK ADA

- Tabel `instructor_approvals`
- Field `users.is_approved` atau `users.approval_status`
- UI "Pending Instructors" di admin panel
- Email "Instructor approved"
- Block/ban user mechanism

## Consequences

### Positive
- **Schema tetap lean** — no new table/fields needed
- **Lower friction** untuk register sebagai instructor
- **Focus moderation di content level** (courses), bukan user level
- **Konsisten dengan project akademik** (no KYC overhead)

### Negative
- Siapapun bisa jadi "instructor" tanpa verifikasi
- Potensi spam account instructor

### Mitigations
- **Quality gate di course level**: course butuh approval sebelum publik
- Admin bisa set course ke `inactive` kapan saja
- Registration pakai email verification (Breeze default)
- Jika masalah besar di future, tambah `is_approved` field di Schema v3

## Register Flow

```
[Register Page]
      ↓
[Pilih role: Student / Instructor]
      ↓
[Email verification (optional, Breeze)]
      ↓
[Auto-login dengan role yang dipilih]
      ↓
[Redirect ke dashboard sesuai role]
```

Tidak ada "Pending Approval" state.

## Admin Panel Update

Halaman "Instructor Management" (`/admin/instructor`) **tetap ada** untuk:
- ✅ List semua instructor dengan statistik (# courses, # students, revenue)
- ✅ View detail instructor (profile + courses)
- ❌ ~~Approve/block~~ — **removed** (no block mechanism)
- ❌ ~~Change role~~ — **removed** (not in scope)

---

## Scope Re-evaluation Trigger

Revisit ADR ini jika:
- Ada komplain spam instructor account
- Komersialisasi butuh KYC
- Instructor approval jadi demand user

---

*Simpler is better untuk MVP akademik.*
