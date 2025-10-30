# GitOps: Branching and Integration


To maintain clean, predictable, and auditable CI/CD pipelines — ensuring:

* Code changes are reviewed before merging.
* Environments (master, staging, prod) mirror the Git branches.
* Deployment is automated, reproducible, and traceable

---

## 1. Git Branch

| Branch        | Purpose                                                    | Environment           | Deployment Trigger                        | Access Level |
| ------------- | ---------------------------------------------------------- | --------------------- | ----------------------------------------- | ------------ |
| **`master`**  | Active development branch; feature integration and testing | **Development (DEV)** | N/A                                       | Vendors      |
| **`staging`** | Pre-production verification and validation                 | **Staging (STG/UAT)** | On merge or push                          | IR, Vendors  |
| **`prod`**    | Production-ready, stable releases only                     | **Production (PROD)** | On merge from `staging` (manual approval) | IR, IHA      |


---

## 2. Workflow Process

### 2.1 Feature Development

```lua
feature/*  --->  master  --->  staging  --->  prod
                 (CI)       (UAT/QA)     (Production)

```

* Developers create feature branches from `master`:

  ```bash
  git checkout master
  git pull origin master
  git checkout -b feature/<feature-name>
  ```
* Code is developed, committed, and tested locally.
* Merge Request (MR) is created to merge the feature into `master`.

**Code Review / Merge to Master**

* Code review approved by at least **1 senior Vendor**.
* CI pipeline passed (lint, unit test, build check).

---

### 2.2 Release to `staging` 

Create a **Merge Request** from `master` → `staging`.
to integrate code in a UAT/Staging environment before production.

* Peer review required.
* CI builds and tests must pass.
* Merge when approved.

     ```bash
     git checkout staging
     git merge master
     git push origin staging
     ```
     
→ On merge:

* CI pipeline will trigger automatically after merged and pushed
* Docker container registry images tagged as `:staging-$commit_id`.
* Scheduling deployment to the **Staging environment**, everyday at 11am,5pm and 12am

---

### 2.3 Release to `prod`

Create a **Merge Request** from `staging` → `prod`.
to integrate code in a UAT/Staging environment before production.

  1. Create PR from `staging` → `prod`.

     ```bash
     git checkout prod
     git merge staging
     git push origin prod
     ```
  2. CI/CD pipeline deploys automatically to **PRODUCTION**.
  3. Post-deployment validation (smoke tests, monitoring).
  
---

## 3. 👥 Roles and Responsibilities

| Role                     | Responsibilities                                                                                                                                      |
| ------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Vendors - Developers** | - Create feature branches<br>- Implement and test code locally<br>- Submit MRs to `prod` branch<br>- Fix issues from code review or pipeline failures |
| **IR QA**                | - Verify change on `staging`<br>- Perform regression and acceptance tests<br>- Approve for production deployment                                      |
| **Inhouse Developers**   | - Manage CI/CD pipelines<br>- Scheduling deployments for each branch<br>- Ensure rollback procedures are in place<br>- Maintain GitOps manifests      |

## 4. Rollback Procedure

1. Identify failed deployment via monitoring/logs.
2. Rollback using GitOps revert:

   ```bash
   git revert <bad-commit>
   git push origin <branch>
   ```
3. Redeploy via GitOps sync (ArgoCD/Flux).
4. Confirm system recovery.

---

## 6. Versioning & Tag Policy

* Follow **Semantic Versioning**: `vMAJOR.MINOR.PATCH`
* Tags applied only on the **prod** branch.
* Example:

  ```
  v1.0.0 – Initial release
  v1.1.0 – Feature update
  v1.1.1 – Bugfix
  ```


---
