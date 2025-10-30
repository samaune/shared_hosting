## 🧭 **GitOps & DevOps SOP: Branching and Deployment Strategy**

### 1. 🎯 **Purpose**

To maintain clean, predictable, and auditable CI/CD pipelines — ensuring:

* Code changes are reviewed before merging.
* Environments (`dev`, `staging`, `prod`) mirror the Git branches.
* Deployment is automated, reproducible, and traceable.

---

## 2. 🏗️ **Branch Model Overview**

| Branch            | Purpose                          | Environment    | Deployment Trigger                        |
| ----------------- | -------------------------------- | -------------- | ----------------------------------------- |
| `master` / `main` | Active development & integration | **Dev**        | On merge or push                          |
| `staging`         | Pre-production QA testing        | **Staging**    | On merge from `master`                    |
| `prod`            | Stable production code           | **Production** | On merge from `staging` (manual approval) |

---

## 3. 🔄 **Branch Flow Diagram**

```
feature/*  --->  master  --->  staging  --->  prod
                 (CI)       (UAT/QA)     (Production)
```

---

## 4. ⚙️ **Development Workflow**

### 🧩 Step 1 — Feature Development

Developers create feature branches off `master`:

```bash
git checkout master
git pull
git checkout -b feature/login-page
```

After coding:

```bash
git push origin feature/login-page
```

> ✅ **CI (GitLab/GitHub Actions)** runs unit tests and linters on push.

---

### 🧾 Step 2 — Code Review / Merge to Master

Create a **Merge Request / Pull Request** into `master`.

* Peer review required.
* CI builds and tests must pass.
* Merge when approved.

→ On merge:

* Deploys automatically to the **Dev environment**.
* Optional: creates Docker images tagged as `:dev`.

---

### 🧪 Step 3 — Promote to Staging

Once the dev environment is verified:

```bash
git checkout staging
git merge master
git push origin staging
```

→ CI/CD triggers deployment to the **Staging** cluster/environment:

* Integration and acceptance tests.
* QA, UAT, and smoke tests.
* Docker image tagged as `:staging`.

---

### 🚀 Step 4 — Promote to Production

After approval (manual or via GitOps policy):

```bash
git checkout prod
git merge staging
git push origin prod
```

→ Deployment to **Production**:

* Rollout via Helm/Kustomize/ArgoCD/FluxCD.
* Tag image as `:prod` or `:vX.Y.Z`.
* Notify via Slack/Discord/email webhook.

---

## 5. 🔐 **GitOps Deployment Rules**

If using **ArgoCD** or **FluxCD**:

* Each environment (`dev`, `staging`, `prod`) has its **own folder or Kustomize overlay** in a GitOps repo:

  ```
  gitops-repo/
  ├── dev/
  ├── staging/
  └── prod/
  ```

* Example:

  ```bash
  apps/
    myportal/
      base/
      overlays/
        dev/
        staging/
        prod/
  ```

* ArgoCD watches the appropriate branch or path:

  * `master` → `/overlays/dev`
  * `staging` → `/overlays/staging`
  * `prod` → `/overlays/prod`

---

## 6. 🧰 **CI/CD Example (GitLab CI)**

```yaml
stages:
  - build
  - test
  - deploy

build:
  stage: build
  script:
    - docker build -t $CI_REGISTRY_IMAGE:$CI_COMMIT_SHA .
    - docker push $CI_REGISTRY_IMAGE:$CI_COMMIT_SHA

deploy_dev:
  stage: deploy
  only:
    - master
  script:
    - kubectl apply -f k8s/dev

deploy_staging:
  stage: deploy
  only:
    - staging
  script:
    - kubectl apply -f k8s/staging

deploy_prod:
  stage: deploy
  only:
    - prod
  when: manual
  script:
    - kubectl apply -f k8s/prod
```

---

## 7. 🛡️ **Security & Policy**

* Protect `staging` and `prod` branches:

  * Allow only merge requests (no direct commits).
  * Require approvals and passing CI checks.
* Tag releases on production merges:

  ```bash
  git tag -a v1.2.3 -m "Release version 1.2.3"
  git push origin v1.2.3
  ```

---

## 8. 📜 **Audit & Rollback**

* Every deployment corresponds to a Git commit hash.
* Rollback via:

  ```bash
  git revert <commit>
  git push origin prod
  ```
* GitOps tools automatically sync to the reverted state.

---

## ✅ Summary

| Environment | Branch    | Deployment | Approval     |
| ----------- | --------- | ---------- | ------------ |
| Dev         | `master`  | Auto       | CI only      |
| Staging     | `staging` | Auto       | QA/UAT       |
| Production  | `prod`    | Manual     | Manager/Lead |


