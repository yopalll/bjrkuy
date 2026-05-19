# ADR-007: Role Enum Pakai `user` (DB), Tapi UI/Folder/Controller Pakai "Student"

**Status:** ✅ Accepted
**Date:** 14 Mei 2026
**Decision By:** Yosua (PM) — default decision via audit recommendation

---

## Context

Saat ini ada inkonsistensi naming di sekitar role:

| Layer | Nama |
|-------|------|
| DB enum value | `'user'` |
| DB table | `users` |
| Route prefix | `/user/*` |
| Route name | `user.dashboard` |
| Folder | `Backend/Student/` |
| Controller | `StudentDashboardController` |
| Model scope | `scopeStudents()` |
| UI Bahasa Indonesia | "Siswa" |
| UI English | "Student" |

Agent/developer bingung: kapan pakai "user", kapan pakai "student"?

## Decision

**Keep the current duality**, but document it explicitly. Tidak refactor Schema.

### Rule

| Konteks | Pakai |
|---------|-------|
| DB schema (enum, column) | `user` |
| DB table name | `users` (plural, Laravel convention) |
| Route URL prefix | `/user/*` (untuk match DB clarity) |
| Route name | `user.*` (dot notation, match URL) |
| Folder path | `Backend/Student/` (business term) |
| Controller class | `Student*Controller` (business term) |
| Model method | `scopeStudents()`, `isStudent()` (business term) |
| Variable | `$student` untuk business context, `$user` untuk DB/generic |
| UI text (id) | "Siswa" |
| UI text (en) | "Student" |

### Rationale untuk DB enum `user`

1. Konsisten dengan nama tabel `users` — semua pengguna sistem
2. Reference project (YouTubeLMS) pakai `user` — backward-compat lebih mudah
3. Ubah ke `student` butuh Schema v3 migration yang disruptive
4. Cost > benefit untuk project akademik

### Rationale untuk business term "Student"

1. Lebih descriptive — "Student" jelas refer ke pembeli kursus, "user" ambigu (admin juga user)
2. Konsisten dengan domain (LMS)
3. Controller name lebih readable: `StudentDashboardController` > `UserDashboardController`

## Consequences

### Positive
- No schema migration needed
- Business logic code readable
- Documentation di `GLOSSARY.md` resolve ambiguitas

### Negative
- **Initial learning curve** untuk agent/dev baru
- Butuh glossary sebagai reference

### Mitigations
- **Mandatory read `GLOSSARY.md`** untuk semua agent (ditambah di `AGENT_GUIDELINES.md`)
- Consistent code comments saat pakai "user" vs "student"
- Linter rule (future): warn jika `Student` dipakai di migration/config

## Examples

```php
// ✅ BENAR
$student = auth()->user();  // Variable name = "student" (business context)
if ($student->isStudent()) {  // isStudent() method (business)
    $orders = $student->orders()->with('course')->get();
}

// DB query tetap pakai 'user'
User::where('role', 'user')->get();

// Atau pakai scope untuk abstraction
User::students()->get();  // scope name = "students" (business)
```

```php
// ✅ BENAR (controller)
class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();  // business context
        return view('backend.student.dashboard', compact('student'));
    }
}
```

```blade
{{-- ✅ BENAR (UI) --}}
<h1>Dashboard Siswa</h1>  {{-- Indonesian UI --}}
<p>Selamat datang, {{ $student->name }}</p>
```

## Alternatives Considered

1. **Refactor semua ke "student"** — Rejected: Schema v3 migration effort, disruptive
2. **Refactor semua ke "user"** — Rejected: less descriptive, inkonsisten dengan domain
3. **Status quo tanpa docs** — Rejected: AI agent akan selalu bingung

---

*Dual terminology adalah trade-off yang conscious. Baca Glossary untuk clarity.*
