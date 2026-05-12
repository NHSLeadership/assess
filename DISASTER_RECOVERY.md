# Disaster Recovery Reference

This application repository does not own the Kubernetes or infrastructure disaster recovery procedures. For recovery guidance, use the dedicated infrastructure and CI/CD repositories instead.

## Where to find recovery guidance

- https://github.com/NHSLeadership/tofu-infrastructure : infrastructure repository with disaster recovery and cluster rebuild procedures.
- https://github.com/NHSLeadership/helm-charts : Helm charts for application settings and deployment configuration.
- https://github.com/NHSLeadership/argocd : ArgoCD repository for application restore, deployment rollback, and recovery steps.

## What this repository covers

- This document is intentionally minimal.
- App-specific recovery actions should be handled by the appropriate infrastructure or deployment repos.

## Security contacts

- See SECURITY.md for vulnerability reporting and cybersecurity contacts only.
- For operational or disaster recovery ownership, use the infrastructure and ArgoCD repositories listed above.
