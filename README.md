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
Create `Utility Files` and `Logic Hooks` by clicking respective buttons on the record detail view page, make `quick repair`.

Then go to `Administration` -> `Workflows`.
Create one or more workflows for the module.
`Bean Type Value` field is related to type field in workflow module record.
`Status field` is the field that will change its value on workflow events.
Create `Utility Fields` and `Status Settings` by clicking buttons, make quick repair.

Go to `Administration` -> `Workflow Statuses`.
Create statuses for your module.
Set `Role` and functions. `Users in Role within Group` for all functions fields is appropriate for most cases.

Now you can create events in `Administration` -> `Workflow Events`.
Leave `Status 1` empty only for initial events, i.e. for first statuses.
Notes: `SendNotificationCopy` function depends on `SugarBeanMailer` package.
`Required Fields for Status` (`SFFormFieldsRequired`) depends on `SecurityForms` package.

Finally, go to `Administration` -> `Studio`.
Select your module -> `Layouts` -> `Detail View`.
Create panel with fields: `Change Status`, `Change Assigned User` and `Assigned Users`.
`Change Status` field is required for others.
`Confirm List` field is not ready to use.

Create a record of your module.
Ensure that your user has role from first workflow status and your user and module record have common groups.
