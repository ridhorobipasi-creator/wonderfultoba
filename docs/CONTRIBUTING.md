# 🤝 Contributing to Wonderful Toba

> Panduan kontribusi untuk developer yang ingin berkontribusi pada project Wonderful Toba

---

## 📋 Daftar Isi

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Git Workflow](#git-workflow)
- [Testing Guidelines](#testing-guidelines)
- [Documentation](#documentation)
- [Pull Request Process](#pull-request-process)

---

## Code of Conduct

### Our Pledge

Kami berkomitmen untuk menjaga lingkungan yang ramah, profesional, dan inklusif untuk semua kontributor.

### Our Standards

**Perilaku yang Diharapkan:**
- ✅ Menggunakan bahasa yang ramah dan profesional
- ✅ Menghormati pendapat dan pengalaman yang berbeda
- ✅ Menerima kritik konstruktif dengan baik
- ✅ Fokus pada apa yang terbaik untuk project
- ✅ Menunjukkan empati terhadap kontributor lain

**Perilaku yang Tidak Dapat Diterima:**
- ❌ Penggunaan bahasa atau gambar yang tidak pantas
- ❌ Trolling, komentar menghina, atau serangan personal
- ❌ Harassment dalam bentuk apapun
- ❌ Mempublikasikan informasi pribadi orang lain
- ❌ Perilaku tidak profesional lainnya

---

## Getting Started

### Prerequisites

Pastikan Anda sudah menginstall:
- PHP >= 8.3
- Composer >= 2.5
- Node.js >= 18
- MySQL >= 8.0 atau PostgreSQL >= 13
- Git

### Setup Development Environment

```bash
# 1. Fork repository
# Klik tombol "Fork" di GitHub

# 2. Clone fork Anda
git clone https://github.com/YOUR_USERNAME/wonderfultoba.git
cd wonderfultoba

# 3. Add upstream remote
git remote add upstream https://github.com/wonderfultoba/wonderfultoba.git

# 4. Install dependencies
composer install
npm install

# 5. Setup environment
cp .env.example .env
php artisan key:generate

# 6. Setup database
touch database/database.sqlite
php artisan migrate --seed

# 7. Build assets
npm run dev

# 8. Run development server
php artisan serve
```

### Verify Installation

```bash
# Run tests
composer test

# Check code style
./vendor/bin/pint --test

# Access application
# Open http://localhost:8000
```

---

## Development Workflow

### 1. Create Feature Branch

```bash
# Update main branch
git checkout main
git pull upstream main

# Create feature branch
git checkout -b feature/your-feature-name
```

### 2. Make Changes

```bash
# Make your changes
# Edit files...

# Test your changes
composer test
./vendor/bin/pint

# Commit changes
git add .
git commit -m "feat: add your feature description"
```

### 3. Keep Branch Updated

```bash
# Fetch upstream changes
git fetch upstream

# Rebase on main
git rebase upstream/main

# Resolve conflicts if any
# Edit conflicted files...
git add .
git rebase --continue
```

### 4. Push Changes

```bash
# Push to your fork
git push origin feature/your-feature-name
```

### 5. Create Pull Request

1. Go to your fork on GitHub
2. Click "New Pull Request"
3. Select your feature branch
4. Fill in PR template
5. Submit PR

---

## Coding Standards

### PHP Code Style

Kami menggunakan **Laravel Pint** untuk code style.

```bash
# Check code style
./vendor/bin/pint --test

# Fix code style
./vendor/bin/pint
```

**Key Rules:**
- PSR-12 coding standard
- 4 spaces indentation
- No trailing whitespace
- Unix line endings (LF)
- File must end with newline

**Example:**

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Display a listing of packages.
     */
    public function index(Request $request)
    {
        $packages = Package::where('status', 'active')
            ->with('city')
            ->paginate(10);

        return view('packages.index', compact('packages'));
    }

    /**
     * Store a newly created package.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $package = Package::create($validated);

        return redirect()
            ->route('packages.show', $package)
            ->with('success', 'Package created successfully');
    }
}
```

### Blade Templates

```blade
{{-- Good --}}
<div class="container">
    @if ($packages->count() > 0)
        @foreach ($packages as $package)
            <x-package-card :package="$package" />
        @endforeach
    @else
        <p>No packages found.</p>
    @endif
</div>

{{-- Bad --}}
<div class="container">
@if($packages->count()>0)
@foreach($packages as $package)
<x-package-card :package="$package"/>
@endforeach
@else
<p>No packages found.</p>
@endif
</div>
```

### JavaScript (Alpine.js)

```javascript
// Good
<div x-data="{
    open: false,
    selectedCity: 'all',
    get filteredPackages() {
        return this.packages.filter(p => 
            this.selectedCity === 'all' || p.cityId === this.selectedCity
        );
    }
}">
    <!-- Content -->
</div>

// Bad
<div x-data="{open:false,selectedCity:'all',get filteredPackages(){return this.packages.filter(p=>this.selectedCity==='all'||p.cityId===this.selectedCity)}}">
```

### Database Migrations

```php
// Good
Schema::create('packages', function (Blueprint $table) {
    $table->id();
    $table->string('slug')->unique();
    $table->string('name');
    $table->longText('description');
    $table->double('price')->default(0);
    $table->json('images');
    $table->foreignId('cityId')
        ->nullable()
        ->constrained('cities')
        ->nullOnDelete();
    $table->timestamps();
});

// Bad
Schema::create('packages',function(Blueprint $table){
$table->id();$table->string('slug')->unique();
$table->string('name');$table->text('description');
$table->decimal('price',10,2);$table->json('images');
$table->unsignedBigInteger('cityId')->nullable();
$table->timestamps();});
```

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| **Classes** | PascalCase | `PackageController` |
| **Methods** | camelCase | `getPackages()` |
| **Variables** | camelCase | `$selectedCity` |
| **Constants** | UPPER_SNAKE_CASE | `MAX_UPLOAD_SIZE` |
| **Database Tables** | snake_case | `package_tiers` |
| **Database Columns** | camelCase | `createdAt` |
| **Routes** | kebab-case | `/tour/package-detail` |
| **Blade Files** | kebab-case | `package-detail.blade.php` |

---

## Git Workflow

### Branch Naming

```
feature/feature-name      # New features
bugfix/bug-description    # Bug fixes
hotfix/critical-fix       # Critical production fixes
refactor/refactor-name    # Code refactoring
docs/documentation-update # Documentation updates
test/test-description     # Test additions
chore/maintenance-task    # Maintenance tasks
```

**Examples:**
```
feature/payment-integration
bugfix/booking-validation
hotfix/security-patch
refactor/controller-optimization
docs/api-documentation
test/booking-controller
chore/dependency-update
```

### Commit Messages

Kami menggunakan **Conventional Commits** format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting)
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks
- `perf`: Performance improvements
- `ci`: CI/CD changes

**Examples:**

```bash
# Feature
git commit -m "feat(booking): add payment integration"

# Bug fix
git commit -m "fix(api): resolve null pointer in package endpoint"

# Documentation
git commit -m "docs(readme): update installation instructions"

# Refactoring
git commit -m "refactor(controller): optimize database queries"

# Breaking change
git commit -m "feat(api): change authentication to token-based

BREAKING CHANGE: Session-based auth is no longer supported"
```

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Checklist
- [ ] Code follows project style guidelines
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] Tests added/updated
- [ ] All tests passing
- [ ] No new warnings

## Testing
Describe testing performed

## Screenshots (if applicable)
Add screenshots

## Related Issues
Closes #123
```

---

## Testing Guidelines

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PackageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_packages()
    {
        // Arrange
        Package::factory()->count(3)->create();

        // Act
        $response = $this->get('/api/packages');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_package()
    {
        // Arrange
        $data = [
            'name' => 'Test Package',
            'price' => 1000000,
            'duration' => '3D2N',
        ];

        // Act
        $response = $this->post('/api/packages', $data);

        // Assert
        $response->assertStatus(201);
        $this->assertDatabaseHas('packages', ['name' => 'Test Package']);
    }
}
```

### Running Tests

```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/PackageControllerTest.php

# Run with coverage
php artisan test --coverage

# Run specific test method
php artisan test --filter=it_can_list_packages
```

### Test Coverage

Minimum test coverage: **80%**

```bash
# Generate coverage report
php artisan test --coverage --min=80
```

---

## Documentation

### Code Comments

```php
/**
 * Get filtered packages based on criteria.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getPackages(Request $request)
{
    // Filter packages by status
    $query = Package::where('status', 'active');

    // Apply city filter if provided
    if ($request->has('city')) {
        $query->where('cityId', $request->city);
    }

    return response()->json($query->get());
}
```

### API Documentation

Gunakan format berikut untuk API endpoints:

```php
/**
 * @api {get} /api/packages Get Packages
 * @apiName GetPackages
 * @apiGroup Package
 *
 * @apiParam {String} [city] Filter by city ID
 * @apiParam {String} [status] Filter by status
 *
 * @apiSuccess {Object[]} packages List of packages
 * @apiSuccess {Number} packages.id Package ID
 * @apiSuccess {String} packages.name Package name
 * @apiSuccess {Number} packages.price Package price
 *
 * @apiSuccessExample Success-Response:
 *     HTTP/1.1 200 OK
 *     [
 *       {
 *         "id": 1,
 *         "name": "Danau Toba 3D2N",
 *         "price": 1500000
 *       }
 *     ]
 */
```

### README Updates

Jika menambah fitur baru, update:
- README.md (quick start)
- PROJECT.md (detailed docs)
- CHANGELOG.md (version history)

---

## Pull Request Process

### Before Submitting

1. **Update your branch**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

2. **Run tests**
   ```bash
   composer test
   ```

3. **Check code style**
   ```bash
   ./vendor/bin/pint
   ```

4. **Update documentation**
   - Update relevant .md files
   - Add code comments
   - Update CHANGELOG.md

### PR Checklist

- [ ] Branch is up to date with main
- [ ] All tests passing
- [ ] Code style is correct
- [ ] Documentation updated
- [ ] Commit messages follow convention
- [ ] No merge conflicts
- [ ] PR description is clear
- [ ] Screenshots added (if UI changes)

### Review Process

1. **Automated Checks**
   - CI/CD pipeline runs
   - Tests must pass
   - Code style must pass

2. **Code Review**
   - At least 1 approval required
   - Address review comments
   - Update PR as needed

3. **Merge**
   - Squash and merge
   - Delete branch after merge

### After Merge

```bash
# Update your local main
git checkout main
git pull upstream main

# Delete feature branch
git branch -d feature/your-feature-name
git push origin --delete feature/your-feature-name
```

---

## Common Tasks

### Adding a New Model

```bash
# 1. Create migration
php artisan make:migration create_table_name_table

# 2. Create model
php artisan make:model ModelName

# 3. Create factory
php artisan make:factory ModelNameFactory

# 4. Create seeder
php artisan make:seeder ModelNameSeeder

# 5. Run migration
php artisan migrate

# 6. Write tests
php artisan make:test ModelNameTest
```

### Adding a New Controller

```bash
# 1. Create controller
php artisan make:controller ModelNameController

# 2. Add routes
# Edit routes/web.php or routes/api.php

# 3. Create views (if needed)
# Create blade files in resources/views

# 4. Write tests
php artisan make:test ModelNameControllerTest
```

### Adding a New API Endpoint

```bash
# 1. Add route in routes/api.php
Route::get('/endpoint', [Controller::class, 'method']);

# 2. Implement controller method
public function method(Request $request) {
    // Implementation
}

# 3. Write test
public function test_endpoint() {
    $response = $this->get('/api/endpoint');
    $response->assertStatus(200);
}

# 4. Document in PROJECT.md
```

---

## Getting Help

### Resources

- **Documentation:** See PROJECT.md
- **API Docs:** See API section in PROJECT.md
- **Deployment:** See DEPLOYMENT.md
- **Migration:** See MIGRATION.md

### Contact

- **Email:** dev@wonderfultoba.com
- **Slack:** #wonderfultoba-dev
- **Issues:** GitHub Issues

### Questions?

Jangan ragu untuk:
1. Open an issue
2. Ask in Slack channel
3. Email development team

---

## Recognition

Kontributor akan diakui di:
- CHANGELOG.md
- Project README.md
- Release notes

---

## License

By contributing, you agree that your contributions will be licensed under the same license as the project (Proprietary).

---

**Thank you for contributing to Wonderful Toba! 🎉**

---

**Last Updated:** April 30, 2026  
**Maintained By:** Wonderful Toba Development Team
