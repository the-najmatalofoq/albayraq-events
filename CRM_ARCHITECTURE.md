# CRM Module Architectural Standard

# note: ensure all the files have the fully import at the top of the file,

# note: check the bootstrap/providers.php to ensure the module is registerd,

# note: use the LoadTranslations to load each module Translations

# note: check the bootstrap/app.php to know the shape of the format and exceptions

# note: ensure all the files have the import at the top, and have the (declare_strict=1) and also have the only one inline comment at the top of the file the mention the file path

This document defines the persistent architectural standard for all modules within the CRM system. It serves as the single source of truth for maintainability and cross-session consistency.

## 🏗️ Core Architecture

We follow a **Modular DDD** (Domain-Driven Design) architecture with a strict **CQRS** (Command Query Responsibility Segregation) pattern at the Application layer.

### Directory Structure (Standard)

```
modules/{ModuleName}/
├── Application/
│   ├── Commands/Crm/   # Write operations (Suffix: Command/Handler)
│   ├── Handlers/Crm/   # Logic orchestration
│   └── Queries/Crm/    # Read operations (Suffix: Query/Handler)
├── Domain/
│   ├── Entity/         # Rich domain aggregates
│   ├── Repository/     # Interfaces
│   └── ValueObject/    # Typed identities and primitives
├── Infrastructure/
│   ├── Persistence/Eloquent/
│   │   ├── Models/     # Laravel Models (No CRM prefix)
│   │   └── Factories/  # Data generation
│   ├── Persistence/Seeders/
│   └── Routes/Crm/     # api.php (CRM-prefixed routes)
└── Presentation/Http/
    ├── Action/Crm/     # Invokable controller actions
    ├── Request/Crm/    # FormRequests (extends BaseFilterRequest)
    └── Presenter/      # DTO transformation
```

## 🛠️ Implementation Rules

### 1. Prefixes & Namespaces

- **CRM Namespace**: All classes in `Application`, `Presentation`, and `Routes` must use the `Crm` prefix (e.g., `CrmCreateUserAction`).
- **Models & Domain**: Domain entities and Eloquent models do **NOT** use the `Crm` prefix.

### 2. Advanced CRUD Requirements

Every CRM module must implement the following 8 operations:

1.  **List**: Filtered list without pagination.
2.  **Paginated**: Filtered list with pagination.
3.  **Show**: Get single resource (with trashed support).
4.  **Create**: Resource creation.
5.  **Update**: Resource modification.
6.  **Soft Delete**: Move to trash.
7.  **Hard Delete**: Permanent removal.
8.  **Restore**: Recover from trash.

### 3. Shared Logic

- **Filtering**: Use `FilterCriteria` and `FilterableRepositoryInterface`.
- **Pagination**: Use `PaginationCriteria` and `JsonResponder->paginated()`.
- **Soft Deletes**: Use Laravel's `SoftDeletes` trait and include a `trashed` query parameter in fetch operations.

## 🏁 Module Status

| Module              | CRM Implementation | Soft Deletes | Filtering | Status       |
| :------------------ | :----------------: | :----------: | :-------: | :----------- |
| EventRoleAssignment |         ✅         |      ❌      |    ❌     | Phase 1 Done |
| EventRoleCapability |         🏗️         |      🏗️      |    🏗️     | In Progress  |

---

_Last Updated: 2026-04-11_
