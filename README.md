# SuiteCRM Workflow Package

New modules:
 - WFModules
 - WFWorkflows
 - WFStatuses
 - WFEvents

The links appear in `Administration`.

To add workflow support for any module go to `Administration` -> `Workflow Modules`.
Add a record for your module.
Depict module name and type field.
`Type field` is used to decide which workflow will be used when bean record is created.
Create `Utility Files` and `Logic Hooks` by clicking respective buttons on the record detail view page.
Remove `Utility Files` and `Logic Hooks` first if you want to change module name or remove record.

Then go to `Administration` -> `Workflows`.
Create one or more workflows for the module.
`Bean Type Value` field is related to type field in workflow module record.
`Status field` is the field that will change its value on workflow events.
Old status values will no longer be available.
Create `Utility Fields` and `Status Settings` by clicking buttons.
Remove `Utility Fields` and `Status Settings` first if you want to change module name or status field or remove record.

Run `Administration` -> `Repair` -> `Quick Repair and Rebuild`.
Synchronize database.
New fields must be created in your module: `wf_id`, `confirm_list`.
Status field may be modified.

Go to `Administration` -> `Workflow Statuses`.
Create statuses for your module.
Set `Role` and functions. `Users in Role within Group` for all functions fields is appropriate for most cases.

Now you can create events in `Administration` -> `Workflow Events`.
Leave `Status 1` empty only for initial events, i.e. for first statuses.
Notes: `SendNotificationCopy` function depends on `SugarBeanMailer` package.
`Required Fields for Status` (`SFFormFieldsRequired`) depends on `SecurityForms` package.

Finally, go to `Administration` -> `Studio`.
Select your module -> `Layouts` -> `Detail View`.
Create panel with control fields: `Change Status`, `Change Assigned User` and `Assigned Users`.
`Change Status` field is required for others.
`Confirm List` field is not ready to use.
Notes for SuiteCRM Studio: if new panel is on top of layout, make sure that its `Display Type` is `Tab`.
Change other panels display type to `Panel` if new panel display type is disabled.
Edit panel name before adding fields.
Click `Save & Deploy`.

Create a record of your module.
Ensure that your user has role from first workflow status and your user and module record share same group.

Note: Assigned user can be changed only by new field.
Standard field will throw exception if new user is not allowed.
(TODO: fix Suite theme to work with hideIf option like Sugar CE do)

## See also

 - [suitecrm-workflow-opportunities](https://github.com/hardsoft321/suitecrm-workflow-opportunities) creates utility files for `Opportunities` module (you still need to add control fields to DetailView).
 - [demo321-en-data](https://github.com/hardsoft321/demo321-en-data) inserts demo data: some users, roles, groups and workflow for Opportunities.
[dbgit](https://github.com/hardsoft321/suitecrm-dbgit) package required.
 - [suitecrm-workflow-bpmn](https://github.com/hardsoft321/suitecrm-workflow-bpmn) displays workflow in BPM notation.
