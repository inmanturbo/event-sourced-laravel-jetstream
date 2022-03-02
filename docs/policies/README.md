# Policy

## Main User

* Each Site running this app will have up to one `main user`.
* The user's profile will be used to brand the site.
* Admins on the Main User's Teams can manage the Main User's Profile.
* Only Super Admins can Change the Main User for the site
* The Main User can be an Organization
  * Organization are stored in the users table with `type=organization`
  * Organizations are just users with modified privilege and security features

## Teams and Organizations

### Who can create and manage Organizations?

* Super Admins
* Upgraded Users

### Who can create and manage Teams?

* Super Admins
* Global Admins
* Upgraded Users
* Team admins -- Users who are admins on one or more teams

### Who can create and manage teams for organizations?

* Super Admins
* Admins on one of the Organization's teams
  * Organization admins are members who are admins on one or more teams for that Organization
  * Being an admin for an Organization requires team membership
    * In order to make an Organization Admin, a user must be assigned to one of the organization's team as an admin for that team
    * The admin can then create other teams for the organization
    * They WILL NOT implicitly be an admin for all of the organizations teams!
