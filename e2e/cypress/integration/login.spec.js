describe('Login', () => {
    it('Navigation contains login', () => {
        cy.visit('/')

        cy.contains('Login').click()

        cy.url().should('include', '/login')
    })

    context('Form', () => {
        beforeEach(() => {
            cy.visit('#/login')
        })

        it('Check the login form with correct user credentials', () => {

            cy.fixture('user')
                .then((user) => {
                    cy.get('#login-email')
                        .type(user.email)
                        .should('have.value', user.email);

                    cy.get('#login-password')
                        .type(user.password)
                        .should('have.value', user.password);

                    cy.get('button[type=submit]').click()

                    // we should have visible success message
                    cy.get('div.alert-success')
                        .should('be.visible')
                        .and('contain', 'You have been successfully logged in');

                    cy.url().should('include', '/tasks')
                });
        })

        it('Check the login form with incorrect user credentials', () => {

            cy.fixture('user')
                .then((user) => {
                    cy.get('#login-email')
                        .type(user.email)
                        .should('have.value', user.email);

                    cy.get('#login-password')
                        .type('incorrectPassword')
                        .should('have.value', 'incorrectPassword');

                    cy.get('button[type=submit]').click()

                    // we should have visible success message
                    cy.get('div.alert-danger')
                        .should('be.visible')
                        .and('contain', 'Incorrect email or password.');

                    cy.url().should('include', '/login')
                });
        })
    })

    context('Redirection after login', function () {
        beforeEach(function () {
            // login before each test
        })

        it('User should be redirected to /tasks', function () {

            cy.fixture('user')
                .then((user) => {
                    cy.login(user.email, user.password)
                    cy.url().should('include', '/tasks')
                });
        })

        it('Manager should be redirected to /users', function () {

            cy.fixture('manager')
                .then((user) => {
                    cy.login(user.email, user.password)
                    cy.url().should('include', '/users')
                });
        })

        it('Admin should be redirected to /users', function () {

            cy.fixture('admin')
                .then((user) => {
                    cy.login(user.email, user.password)
                    cy.url().should('include', '/users')
                });
        })
    })
})