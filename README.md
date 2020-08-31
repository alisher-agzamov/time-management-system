# Time Management System

- [Project requirements](#project-requirements)
- [Backend](#backend)
- [Frontend](#frontend)
- [Postman collection](#postman-collection)
- [Unit tests](#unit-tests)
- [e2e tests](#e2e-tests)
- [Demo](#demo)
- [API Documentation](#api-documentation)


## Project requirements
Write an application for time management system
- User must be able to create an account and log in. (If a mobile application, this means that more users can use the app from the same phone).
- User can add (and edit and delete) a row describing what they have worked on, what date, and for how long.
- User can add a setting (*Preferred working hours per day*).
- If on a particular date a user has worked under the *PreferredWorkingHourPerDay*, these rows are red, otherwise green.
- Implement at least three roles with different permission levels: a regular user would only be able to CRUD on their owned records, a user manager would be able to CRUD users, and an admin would be able to CRUD all records and users.
- Filter entries by date from-to.
- Export the filtered times to a sheet in HTML:
	- Date: 2018.05.21
	- Total time: 9h
	- Total time: 9h
	- Notes
	    - Note1
	    - Note2
	    - Note3
- REST API. Make it possible to perform all user actions via the API, including authentication (*If a mobile application and you don’t know how to create your own backend you can use Firebase.com or similar services to create the API*).
- In any case, you should be able to explain how a REST API works and demonstrate that by creating functional tests that use the REST Layer directly. Please be prepared to use REST clients like Postman, cURL, etc. for this purpose.
- If it’s a web application, it must be a single-page application. All actions need to be done client-side using AJAX, refreshing the page is not acceptable. (*If a mobile application, disregard this*).
- Functional UI/UX design is needed. You are not required to create a unique design, however, do follow best practices to make the project as functional as possible.
- Bonus: unit and e2e tests.

## Backend
The backend was implemented based on the [Laravel PHP framework](https://laravel.com/).

## Frontend
The frontend was implemented based on the [Vue.js Javascript Framework](https://vuejs.org/) and [Bootstrap](https://getbootstrap.com/).

## Postman Collection
To have a convenient way directly test API there was created [Postman collection](https://learning.postman.com/docs/sending-requests/intro-to-collections/) that contains all available endpoints. 
The collection can be found in the root directory. Also, the repository contains a JSON file with the prepopulated development environment variables.

## Unit tests
The backend code is covered by unit tests.

## E2e tests
I started coverage of the the project by e2e tests using the [Cypress](http://cypress.io) framework.

## Demo
The demo project can be found by the [link](https://time-management-system.adnet.uz/).

Email | Password | Role
------------ | ------------- | -------------
user@test.com | userPass | user
manager@test.com | managerPass | manager
admin@test.com | adminPass | admin

## API Documentation
The API documentation is generated using the [APIdoc](https://apidocjs.com/) tool based on API annotations in the source code.
It can be found in the apidoc directory or by [the link](https://time-management-system-apidoc.adnet.uz/). 