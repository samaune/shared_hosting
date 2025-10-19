/etc/apache2/
├── apache2.conf          ← Main global config
├── conf-available/       ← Optional config snippets
│   └── security.conf
├── conf-enabled/         ← Enabled configs (symlinks)
├── envvars               ← Environment variables
├── magic                 ← MIME type detection rules
├── mods-available/       ← All modules
├── mods-enabled/         ← Active modules (symlinks)
├── ports.conf            ← Port listening settings
├── sites-available/      ← Virtual host definitions
├── sites-enabled/        ← Active virtual hosts (symlinks)