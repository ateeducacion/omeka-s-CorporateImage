---
name: header-footer-settings
description: "Change custom header/footer configuration or legacy settings compatibility."
---

# Header and footer settings

Start with root `Module.php`, `src/Form/ConfigForm.php`, and `config/module.config.php`.
The actual namespace/module directory is `PersonalizedHeaderFooter`; Composer still contains
legacy template metadata, so do not infer runtime names from its package description.

- Trace form values through `setModuleSetting`, `getModuleSetting`, and `deleteModuleSetting`.
  Flat keys use `personalized_header_footer_`; optional module-scoped methods are compatibility paths.
- Prefer the generic setting when present, fall back to legacy storage only when missing, and remove
  both supported representations on uninstall. Preserve deliberately empty HTML.
- Custom HTML is intentional administrator configuration. Keep authorization at the form boundary;
  do not silently turn markup into plain text or broaden who can configure it.
- `attachListeners()` is currently empty. Verify an actual consumer before documenting public rendering
  as implemented; adding a rendering hook is a feature, not a settings refactor.

Run `make lint` and `make test`; cover legacy-only settings, empty values and uninstall behavior when changed.
The current package recipe still names ModuleTemplate and rewrites the version: inspect it in an isolated
checkout before a release, and do not treat a successful ZIP command as proof of the correct module layout.
