  - belongsTo Question
  - belongsTo User (respondent) optional
- Constraints:
  - Validate value type according to question.type

Cross-cutting concerns
- Timestamps: createdAt, updatedAt for all models
- Soft deletes: Consider `deletedAt` nullable timestamp if required
- Auditing: Record actorId for changes if needed
- Multi-tenancy: Add tenantId if the app is multi-tenant

API surface (suggested endpoints)
- Users
  - GET /api/users
  - GET /api/users/:id
  - POST /api/users
  - PATCH /api/users/:id
  - DELETE /api/users/:id
- Assessments
  - GET /api/assessments
  - GET /api/assessments/:id
  - POST /api/assessments
  - PATCH /api/assessments/:id
  - POST /api/assessments/:id/publish
- Questions
  - POST /api/assessments/:assessmentId/questions
  - PATCH /api/questions/:id
- Responses
  - POST /api/questions/:questionId/responses
  - GET /api/assessments/:id/results
- Authentication & Authorization:
  - JWT bearer tokens, role-based access checks (admin, author, respondent)
  - Owner checks for editing resources

Example payloads
- Submit response (scale)
  {
    "respondentId": "uuid",
    "value": 4,
    "comment": "Consistently exceeds expectations"
  }

Data migration & storage notes
- Use UUIDs as primary keys for portability.
- Index foreign keys and commonly filtered columns (status, authorId).
- Consider storing `value` in a typed column or polymorphic fields for performance.
- If using SQL, define FK constraints and cascade behavior intentionally.

Testing recommendations
- Unit tests for model validations and relationships.
- Integration tests for create/update flows, ownership and RBAC rules.
- Contract tests for API payload shapes.

Open questions / information needed
- Exact model file names and field lists from /Models directory.
- Any custom decorators/annotations in models (e.g., validation rules, ORM annotations).
- Whether soft deletes or multi-tenancy are required.
- Desired persistence (Postgres, MongoDB, etc.).

Next steps (what I need from you)
1. Add the Models directory (or its file list / tree output) to the working set, or paste the Models folder contents here.
2. I will update this scratch_64.md to reflect exact fields, types, relations, validations and generate endpoint examples tailored to your models.


