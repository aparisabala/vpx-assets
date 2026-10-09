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
Panels/
  _Base/                     Shared by every admin layout (not a layout itself):
    Components/<component>/views/   Blade views of each component
    Setup/Views/             Shell (layouts, header, sidebar, footer), auth pages, dashboard, common fragments
    Setup/Assets/            a_px-shell.css / a_px-shell.js (structure, --px-* tokens, sidebar, dark mode),
                             Images/system (logo.svg, favicon.svg, avatar.svg)
  Nova|Horizon|Rail|Aurora|Midnight|Ledger/   Admin layouts:
    Setup/Assets/Css/b_<layout>.css   Tokens and structural overrides of the layout
    PxConfig.php             Google fonts, merged into config/pxcommands.php on px:panel
    <any _Base path>         Optional override of a shared file
  BlankTheme/                Public landing layout (auth=no): Views, Routes, Controllers, Repositories
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

## Adding a layout

1. Copy one of the admin layouts (e.g. `Panels/Nova`) to `Panels/<NewLayout>` and rename its css to `b_<newlayout>.css`.
2. Set the tokens in `:root` (and the dark ones in `:root[data-px-mode="dark"]`), then restyle the shell classes:
   `.px-sidebar`, `.px-topbar`, `.px-main`, `.px-menu-link`, `.px-auth`, `.px-auth-aside` and `.px-auth-card`.
   Add `--px-default-mode: dark` or `--px-default-sidebar: collapsed` to change the defaults.
3. Change `PxConfig.php` to load the layout's fonts. Use a unique key, e.g. `font_<layout>`.
4. Only when CSS is not enough, override a shared view by placing a file at the same path as in `_Base`.
   Keep the `@include` names and the element ids that px.js and the js stubs use (`exampleModal`, `theGlobalLoader`, `frmStore{{model}}`, `dt...`).
5. Run `php artisan px:list` in a project. The new layout appears and supports every component.

Folders starting with `_` are never offered as layouts.

## Never commit

Real SMTP, database or API credentials, server paths, or personal notes. `px:deploy` takes credentials as options and writes them only to the project's `.env`.
