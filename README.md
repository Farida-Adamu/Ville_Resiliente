# Ville Résiliente — WordPress theme

## ⬇️ Download the theme

**[Download ville-resiliente.zip](https://raw.githubusercontent.com/Farida-Adamu/Ville_Resiliente/claude/website-design-update-5tztjv/wordpress-theme/ville-resiliente.zip)**

This link always gives the latest version of the theme, ready to upload. Don't unzip it.

> ⚠️ **Don't use the green "Code → Download ZIP" button.** That downloads the whole repository
> (file name like `Ville_Resiliente-claude-website-design-update-5tztjv.zip`), and WordPress refuses it with
> *"The style.css stylesheet does not contain a valid theme header"* or *"The theme is missing the style.css stylesheet"*.
> The right file is called **`ville-resiliente.zip`**.

## Install or update on the site

1. In WordPress, go to **Appearance → Themes → Add New Theme → Upload Theme**.
2. Choose `ville-resiliente.zip` and click **Install Now**.
3. If the theme is already installed, WordPress asks what to do: click **Replace active with uploaded**.

Posts, pages, images, menus and Customizer settings are kept; only the theme files are replaced.

## What's in this repository

| Path | What it is |
| --- | --- |
| `wordpress-theme/ville-resiliente.zip` | The theme, ready to upload (the file linked above) |
| `wordpress-theme/ville-resiliente/` | The theme's source files |
| `wordpress-theme/build-zip.sh` | Rebuilds the zip from the source folder |
| `index.html`, `recit-lagune.html`, `styles.css`, `assets/` | The original static HTML version of the design, kept for reference |

After changing anything in `wordpress-theme/ville-resiliente/`, run `wordpress-theme/build-zip.sh`
and commit the updated zip together with the change, so the download link above stays current.
