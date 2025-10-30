# GitOps: Branching and Integration


To maintain clean, predictable, and auditable CI/CD pipelines — ensuring:

* Code changes are reviewed before merging.
* Environments (master, staging, prod) mirror the Git branches.
* Deployment is automated, reproducible, and traceable

---

## 1. Git Branch

| Branch        | Purpose                                                    | Environment           | Deployment Trigger                        | Access Level            |
| ------------- | -----------------------------------------------------------| --------------------- | ----------------------------------------- | ----------------------- |
| **`master`**  | Active development branch; feature integration and testing | **Development (DEV)** | On merge or push                          | Vendors                 |
| **`staging`** | Pre-production verification and validation                 | **Staging (STG/UAT)** | On merge from `master`                    | IR, Vendors             |
| **`prod`**    | Production-ready, stable releases only                     | **Production (PROD)** | On merge from `staging` (manual approval) | IR, IHA                 |


---

## 2. 🔄 Workflow Process

### 2.1 Feature Development

```lua
feature/*  --->  master  --->  staging  --->  prod
                 (CI)       (UAT/QA)     (Production)

```

* Vendors create feature branches from `master`:

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

### 2.2 Merge to `master` (Development Integration)

* **Purpose:** Integrate new features for DEV environment testing.
* **Responsible:** Vendors + CI/CD automation.
* **Actions:**

  * After PR approval, merge to `master`.
  * CI pipeline automatically deploys to **DEV** environment.
  * Automated tests and smoke checks are triggered.

---

### 2.3 Promote to `staging`

* **Purpose:** Test integrated code in a UAT/Staging environment before production.
* **Responsible:** DevOps + QA.
* **Actions:**

  1. Create PR from `master` → `staging`.

     ```bash
     git checkout staging
     git merge master
     git push origin staging
     ```
  2. CI/CD pipeline deploys automatically to **STAGING**.
  3. QA performs:

     * Functional testing
     * Regression testing
     * Integration testing
  4. QA signs off for production release.

**Promotion condition:** QA approval required.

---

### 2.4 Promote to `prod`

* **Purpose:** Deploy stable, tested code to the production environment.
* **Responsible:** DevOps + Release Manager.
* **Actions:**

  1. Create PR from `staging` → `prod`.

     ```bash
     git checkout prod
     git merge staging
     git push origin prod
     ```
  2. CI/CD pipeline deploys automatically to **PRODUCTION**.
  3. Post-deployment validation (smoke tests, monitoring).
  4. Tag release version:

     ```bash
     git tag -a vX.Y.Z -m "Release version X.Y.Z"
     git push origin vX.Y.Z
     ```

**Promotion condition:** QA sign-off + Release approval.

---

## 3. 👥 Roles and Responsibilities

| Role                            | Responsibilities                                                                                                                                                |
| ------------------------------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Vendors**                  | - Create feature branches<br>- Implement and test code locally<br>- Submit PRs to `master`<br>- Fix issues from code review or pipeline failures                |
| **QA Engineers**                | - Validate code on `staging`<br>- Perform regression and acceptance tests<br>- Approve for production deployment                                                |
| **DevOps Engineers**            | - Manage CI/CD pipelines<br>- Automate deployments for each branch<br>- Ensure rollback procedures are in place<br>- Maintain GitOps manifests (Helm/Kustomize) |
| **Release Manager / Tech Lead** | - Approve merges to `prod`<br>- Review release notes and tags<br>- Ensure compliance and audit trails<br>- Coordinate rollback if necessary                     |

---

## 4. ⚙️ CI/CD and GitOps Integration

Each branch triggers automated deployment to its target environment through GitOps controllers (e.g., **ArgoCD**, **Flux**, or **Jenkins X**).

| Branch    | Target Environment | Deployment Trigger             |
| --------- | ------------------ | ------------------------------ |
| `master`  | DEV                | Auto on merge                  |
| `staging` | STAGING            | Auto on merge                  |
| `prod`    | PROD               | Manual approval or tag trigger |

---

## 5. 🚨 Rollback Procedure

1. Identify failed deployment via monitoring/logs.
2. Rollback using GitOps revert:

   ```bash
   git revert <bad-commit>
   git push origin <branch>
   ```
3. Redeploy via GitOps sync (ArgoCD/Flux).
4. Confirm system recovery.

---

## 6. 📦 Versioning & Tag Policy

* Follow **Semantic Versioning**: `vMAJOR.MINOR.PATCH`
* Tags applied only on the **prod** branch.
* Example:

  ```
  v1.0.0 – Initial release
  v1.1.0 – Feature update
  v1.1.1 – Bugfix
  ```

---

## 7. ✅ Approval Workflow Summary

| Step | Branch             | Required Approval       | Deployed To |
| ---- | ------------------ | ----------------------- | ----------- |
| 1    | `feature → master` | Vendor + Peer Review | DEV         |
| 2    | `master → staging` | DevOps + QA             | STAGING     |
| 3    | `staging → prod`   | QA + Release Manager    | PROD        |

---
