# vpx-assets

Templates and runtime files used by the `vpx/pxcommands` artisan generators (see `vpx/pxcommands/COMMANDS.md`).
Keep this folder next to the projects (`../vpx-assets`) or point `VPX_ASSETS_PATH` at it.

```
InstallPx/<version>/         Laravel skeleton copied by px:install (providers with //vpx_* markers, base repository,
                             traits, config/pxcommands.php, deploy stubs used by px:deploy)
PxStock/
  Components/<component>/    Theme independent stubs: routes, controllers, requests, repositories, models, js, lang
                             (crud, form, data-table, data-view, load-view, modal, policy)
  Setup/                     Auth panel stubs (login, logout, reset, dashboard, profile setup, middleware, lang)
Panels/<Layout>/
  Components/<component>/views/   Blade views of each component for this theme
  Setup/                     Auth panel views, layouts and Assets/{Css,Js,Images,Fonts} (auth=yes themes)
  Views|Routes|Controllers|Repositories/   Landing panel files (auth=no themes, e.g. BlankTheme)
  PxConfig.php               Optional extra CDN css/js merged into config/pxcommands.php on px:panel
PxLib/
  px-ajax/                   Source of px.js / px.css, the built files in dist/ are copied into projects
  px-cropper/                Image cropper plugin source
```

## Rules for stubs

- `{{placeholder}}` is replaced with values built by the command, such as `{{model}}`, `{{nameDown}}`, `{{nameSpace}}`, `{{repositoryNameSpace}}`, `{{bladeRoute}}`, `{{uri}}` and `{{langKey}}`.
  Blade's `{{ $var }}` is left alone because only exact placeholder names are replaced.
- Never hard-code a model or class name (for example `AdminUser`). Use `{{model}}`, so the stubs work for any model.
- Keep the `//vpx_imports` and `//vpx_attach` markers in generated PHP. Later commands inject code after them.
- File names are case-sensitive on Linux servers. The generators expect the exact names used here (`iRepo.stub`, `Repo.stub`, `Setup/Assets/Css`, ...).

## Adding a theme

1. Copy `Panels/Minible` to `Panels/<NewTheme>`. Minible supports every component.
2. Replace the views and `Setup/Assets` with the new theme's markup and files. Keep the `@include` names and the element ids that the js stubs use (`frmStore{{model}}`, `dt...`).
3. Run `php artisan px:list` in a project. The new layout appears with the components it supports.
4. Any component folder you leave out is reported clearly when someone uses it with that theme.

## Never commit

Real SMTP, database or API credentials, server paths, or personal notes. `px:deploy` takes credentials as options and writes them only to the project's `.env`.
