# Disciple.Tools – Literacy Reports

A small WordPress plugin for **Disciple.Tools** that creates and manages literacy class reports.

## Features

The plugin adds a new **Literacy Report** record type for tracking:

- the report date and submission source;
- the teacher and class;
- present and absent students;
- total student count and attendance rate;
- the current book and lesson;
- the report status: new, reviewed, approved, needs follow-up, or closed.

Each report can be connected to a **class** (a Disciple.Tools group), a **teacher** (a contact), and the contacts representing present or absent students. Related reports are also displayed on group and contact records.

The module grants the required permissions to the following Disciple.Tools roles: administrator, DT Admin, multiplier, dispatcher, marketer, and strategist. When a report is created without a status, it automatically receives the `new` status.

## Requirements

- a WordPress installation;
- the **Disciple.Tools** theme installed and active;
- PHP 7.4 or later, because the plugin uses arrow functions (`fn`).

If Disciple.Tools is not active, the plugin does not load the record type and displays an error notice in the WordPress administration area.

## Installation

1. Copy the `disciple-tools-literacy-reports` folder into `wp-content/plugins/`.
2. Activate **Disciple.Tools – Literacy Reports** from the WordPress administration area.
3. Open the **Literacy Reports** module in Disciple.Tools to create your first report.

## Project structure

```text
disciple-tools-literacy-reports.php  Plugin entry point and Disciple.Tools check
post-type/
└── literacy_report.php             Record type, fields, connections, and permissions
```

## Technical notes

The plugin uses the native `Disciple_Tools_Post_Type_Template` class and Disciple.Tools filters and actions. It therefore relies on the screens, navigation, templates, rewrite rules, and record connections already provided by Disciple.Tools.

Current version: **2.0.0**  
License: **GPL-2.0+**
