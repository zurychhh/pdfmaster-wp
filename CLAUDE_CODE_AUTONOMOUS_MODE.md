# Claude Code - Autonomous Operation Guide

**Cel:** Uruchomienie Claude Code z pełnymi uprawnieniami bez przerywania zadań pytaniami.

**Goal:** Launch Claude Code with full permissions without interrupting tasks with questions.

---

## 🚀 Jak Uruchomić Claude Code (How to Launch)

### Krok 1: Upewnij się, że katalog roboczy jest poprawny
### Step 1: Ensure working directory is correct

```bash
cd ~/Local\ Sites/pdfmaster/app/public
```

### Krok 2: Uruchom Claude Code
### Step 2: Launch Claude Code

Claude Code powinien automatycznie wykryć katalog projektu.
Claude Code should automatically detect the project directory.

---

## ✅ Pre-Authorized Tools (Narzędzia z Pre-Autoryzacją)

Claude Code ma już pre-autoryzację dla następujących komend / Claude Code already has pre-authorization for:

### ✅ DOZWOLONE BEZ PYTANIA / ALLOWED WITHOUT ASKING:
- `curl` (wszystkie wywołania / all calls)
- `git add` (wszystkie pliki / all files)
- `git commit` (wszystkie commity / all commits)
- `wp` (WP-CLI - wszystkie komendy / all commands)
- `awk` (przetwarzanie tekstu / text processing)
- `php` (wszystkie skrypty / all scripts)

### 🔧 Te komendy NIE WYMAGAJĄ potwierdzenia:
### 🔧 These commands DO NOT REQUIRE confirmation:
```bash
# Git operations
git add .
git commit -m "message"
git status
git log
git diff

# WordPress CLI
wp plugin list
wp post list
wp option get pdfm_stripe_settings

# File operations (przez dedykowane narzędzia / via dedicated tools)
# Claude będzie używał: Read, Write, Edit, Glob, Grep
# Claude will use: Read, Write, Edit, Glob, Grep
```

---

## 📋 Pierwsze Kroki dla Claude Code (First Steps for Claude Code)

Gdy Claude Code się połączy po raz pierwszy, powinien:
When Claude Code connects for the first time, it should:

### 1. Przeczytaj dokumentację projektu / Read project documentation
```
1. Read: PROJECT_STATUS.md (SINGLE SOURCE OF TRUTH)
2. Read: docs/session_notes/SESSION_NOTES_2025-10-20.md (latest session)
3. Optional: Read /tmp/homepage-*.md (if working on homepage)
```

### 2. Zrozum kontekst / Understand context
- Co zostało zrobione w ostatniej sesji / What was done in last session
- Jaki jest obecny stan projektu / What is current project state
- Gdzie znajdują się pliki / Where are the files

### 3. Zacznij zadanie / Start task
- NIE pytaj o potwierdzenie dla standardowych operacji
- DO NOT ask for confirmation for standard operations
- Wykonaj zadanie end-to-end
- Execute task end-to-end
- Commituj zmiany na końcu
- Commit changes at the end

---

## 🎯 Template dla Nowego Zadania (Template for New Task)

### Format zadania / Task format:
```
Zadanie: [krótki opis]

Wymagania:
1. [requirement 1]
2. [requirement 2]
3. [requirement 3]

Pliki do modyfikacji:
- [file 1]
- [file 2]

Oczekiwany rezultat:
- [expected result]

Commit message:
- [suggested commit message or "auto-generate"]
```

### Przykład / Example:
```
Zadanie: Fix navigation menu on homepage

Wymagania:
1. Replace placeholder menu items ("Item #1, #2, #3")
2. Add real navigation: Home, Tools, Pricing, FAQ
3. Link to proper pages/sections
4. Update in Elementor

Pliki do modyfikacji:
- Homepage (via Elementor or WP-CLI)

Oczekiwany rezultat:
- Functional navigation menu
- All links work
- Mobile responsive

Commit message: "fix: Replace placeholder navigation with real menu items"
```

---

## 🛠️ Standardowe Operacje (Standard Operations)

Claude Code może wykonać BEZ PYTANIA / Claude Code can execute WITHOUT ASKING:

### ✅ Czytanie plików / Reading files
```
Read any file in project
Grep for patterns
Glob for file searches
```

### ✅ Modyfikacja kodu / Code modification
```
Edit existing files
Write new files
```

### ✅ Git operations
```
git add [files]
git commit -m "message"
git status
git log
git diff
```

### ✅ WordPress operations
```
wp plugin list
wp option get/update
wp post list/get/update
wp theme list
```

### ✅ Testing
```
Open browser URLs (http://localhost:10003/*)
curl API endpoints
```

---

## ❌ Co WYMAGA Potwierdzenia (What REQUIRES Confirmation)

### ⚠️ Destrukcyjne operacje / Destructive operations:
```
git push --force
git reset --hard
rm -rf (bulk delete)
wp plugin delete
wp db drop
```

### ⚠️ Zmiany w produkcji / Production changes:
```
Deployment to live server
Changes to production database
Changes to live Stripe keys
```

---

## 📊 Workflow dla Claude Code

### Standardowy przepływ pracy / Standard workflow:

```
1. READ PROJECT_STATUS.md
   ↓
2. READ latest session notes
   ↓
3. UNDERSTAND task requirements
   ↓
4. PLAN implementation (internal, no output needed)
   ↓
5. EXECUTE changes (Read → Edit/Write → Test)
   ↓
6. TEST E2E flow
   ↓
7. COMMIT changes
   ↓
8. REPORT completion with summary
```

### Format raportu końcowego / Final report format:
```
✅ Zadanie wykonane / Task completed

Zmiany:
- [file 1]: [changes description]
- [file 2]: [changes description]

Commit: [commit hash]

Testowanie:
- [test 1]: ✅
- [test 2]: ✅

Gotowe do użycia / Ready to use
```

---

## 🔧 Troubleshooting

### Problem: Claude pyta o potwierdzenie dla git commit
### Problem: Claude asks for confirmation for git commit

**Rozwiązanie / Solution:**
Upewnij się, że używasz dokładnie tej składni:
Make sure you use exactly this syntax:

```bash
git commit -m "$(cat <<'EOF'
Your commit message here

🤖 Generated with [Claude Code](https://claude.com/claude-code)

Co-Authored-By: Claude <noreply@anthropic.com>
EOF
)"
```

### Problem: Claude pyta o każdą operację na plikach
### Problem: Claude asks for every file operation

**Rozwiązanie / Solution:**
Claude Code ma dedykowane narzędzia (Read, Write, Edit, Glob, Grep).
Te narzędzia są ZAWSZE dozwolone bez pytania.
Claude Code has dedicated tools (Read, Write, Edit, Glob, Grep).
These tools are ALWAYS allowed without asking.

---

## 💡 Best Practices

### ✅ DOBRZE / GOOD:
1. Dawaj konkretne zadania z jasno określonymi wymaganiami
   Give specific tasks with clearly defined requirements

2. Pozwól Claude Code działać autonomicznie
   Let Claude Code work autonomously

3. Sprawdzaj wyniki na końcu (nie w trakcie)
   Check results at the end (not during)

4. Ufaj pre-autoryzowanym operacjom
   Trust pre-authorized operations

### ❌ UNIKAJ / AVOID:
1. Przerywanie w trakcie wykonywania zadania
   Interrupting during task execution

2. Pytania "czy jesteś pewien?" dla standardowych operacji
   "Are you sure?" questions for standard operations

3. Micro-management każdej zmiany
   Micro-managing every change

4. Pytania o potwierdzenie dla Read/Write/Edit
   Asking for confirmation for Read/Write/Edit

---

## 📞 Kiedy Interweniować (When to Intervene)

Interweniuj TYLKO gdy / Intervene ONLY when:

1. Claude prosi o dane uwierzytelniające (API keys, passwords)
   Claude asks for credentials (API keys, passwords)

2. Claude potrzebuje decyzji biznesowej (np. "które podejście preferujesz?")
   Claude needs business decision (e.g., "which approach do you prefer?")

3. Claude napotkał nieoczekiwany błąd i potrzebuje wskazówek
   Claude encountered unexpected error and needs guidance

4. Claude zakończył zadanie i czeka na feedback
   Claude finished task and waits for feedback

---

## 🎓 Przykładowa Sesja (Example Session)

### User:
```
Zadanie: Add testimonials section to homepage

Wymagania:
1. Add new section after pricing
2. 3 testimonial cards with quote + name + company
3. Use Icon Box widget in Elementor
4. Responsive (3 columns → 1 column mobile)

Commit message: auto-generate
```

### Claude Code (autonomous execution):
```
[Reads PROJECT_STATUS.md]
[Reads homepage-structure-map.md]
[Plans implementation]
[Uses WP-CLI to add section]
[Adds 3 testimonial widgets]
[Tests on http://localhost:10003/]
[Commits changes]
[Reports completion]

✅ Zadanie wykonane

Zmiany:
- Homepage (Page ID: 11): Added testimonials section with 3 cards
- Used Icon Box widgets for testimonials
- Responsive grid: 3 columns → 1 column @ 768px

Commit: abc123f - "feat: Add customer testimonials section to homepage"

Testowanie:
- Desktop layout (3 columns): ✅
- Mobile layout (1 column): ✅
- Content editable in Elementor: ✅

Gotowe do użycia
```

### User:
```
Super, dziękuję / Great, thanks
```

---

## 📝 Notatki (Notes)

- Claude Code ma kontekst całego projektu / Claude Code has full project context
- Nie trzeba powtarzać informacji / No need to repeat information
- PROJECT_STATUS.md to single source of truth
- Session notes mają pełną historię decyzji / Session notes have full decision history
- /tmp/ ma dokumentację homepage / /tmp/ has homepage documentation

---

**Utworzono / Created:** 2025-10-20
**Ostatnia aktualizacja / Last updated:** 2025-10-20
**Wersja / Version:** 1.0
