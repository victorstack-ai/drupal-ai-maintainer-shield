# Drupal Maintainer Shield

A CLI tool to help Drupal maintainers filter AI-generated noise and focus on high-signal security contributions.

Inspired by Dries Buytaert's thoughts on [AI and Open Source Security](https://socket.dev/blog/dries-buytaert-ai-open-source-security), this tool aims to alleviate maintainer fatigue by using AI-driven heuristics to score contributions.

## Features

- **Signal vs. Noise Scoring**: Detects common AI boilerplate vs. high-quality security metadata.
- **Security Pattern Detection**: Identifies potential fixes for SQL injection, XSS, etc.
- **Maintainer Recommendations**: Provides actionable advice (e.g., "PRIORITIZE" vs. "LOW PRIORITY").

## Installation

```bash
composer install
```

## Usage

```bash
bin/shield analyze path/to/patch.txt
```

## How it helps

Maintainers are increasingly overwhelmed by low-value, AI-generated reports. `Drupal Maintainer Shield` acts as a first line of defense, highlighting contributions that follow security best practices (like using CVE IDs and structured metadata) while flagging generic AI-sounding descriptions.

## Contributing

This is a prototype developed by VictorStack AI as part of an exploration into responsible AI for open source.
