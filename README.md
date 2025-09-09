# PalStory Superproject

This repository is the superproject that ties together multiple PalStory submodules.

Submodules included:
- `palstory-game` → https://github.com/johlits/palstory-game
- `palstory-lamp` → https://github.com/johlits/palstory-lamp
- `palstory-vscode` → https://github.com/johlits/palstory-vscode

## Quick Start

Clone with all submodules in one go:

```bash
# Recommended
git clone --recurse-submodules https://github.com/johlits/palstory.git
cd palstory
```

If you already cloned without `--recurse-submodules`, initialize them:

```bash
# From repo root
git submodule update --init --recursive
```

## Pulling Updates

Update the superproject and all submodules to the latest tracked commits:

```bash
# From repo root
# Pull superproject changes
git pull
# Update each submodule to the commit tracked by superproject
git submodule update --init --recursive
```

If you want to fetch submodules and fast-forward to their latest remote commits (not just the tracked commits), you can also run:

```bash
git submodule foreach --recursive git fetch
# Optionally checkout a specific branch in each submodule
# git submodule foreach --recursive "git checkout main && git pull"
```

## Working With Submodules

- Each submodule is a full Git repo inside this tree.
- Make changes inside the submodule directory and commit there.
- Then return to the superproject root and commit the submodule pointer update.

Example:

```bash
# Edit inside a submodule
cd palstory-game
# make changes ...
git add -A
git commit -m "Implement feature X"

# Back to superproject to record the new submodule commit pointer
cd ..
git add palstory-game
git commit -m "Bump palstory-game to latest"
```

## Adding New Submodules

```bash
git submodule add https://github.com/owner/repo.git path/to/submodule
# Commit .gitmodules and the new submodule path
git add .gitmodules path/to/submodule
git commit -m "Add submodule path/to/submodule"
```

## Troubleshooting

- If submodule folders appear empty or missing files, run:
  ```bash
  git submodule update --init --recursive
  ```
- To reset a submodule to the tracked commit:
  ```bash
  git submodule update --recursive --checkout
  ```
- On Windows CRLF/LF warnings are harmless. You can configure per repo:
  ```bash
  git config core.autocrlf true
  ```

## Project Notes

- See `palstory-wiki/backlog.md` for high-level goals and roadmap.
- See `palstory-wiki/README.md` for the full documentation index.
- `palstory-lamp` provides the local Docker LAMP stack.
- `palstory-game` contains the game code.
- `palstory-vscode` provides a VS Code helper extension.
 - Database snapshot: `palstory-wiki/story.dump.sql` is a complete DB snapshot provided for reference and emergency recovery only. Always initialize and evolve the database via migrations under `palstory-game/src/html/migrations/` using `migration_runner.php`. Do not import the dump for routine setup.
