define({ "api": [
  {
    "type": "get",
    "url": "/auth/login",
    "title": "3. Logout",
    "version": "1.0.0",
    "group": "1.Auth",
    "permission": [
      {
        "name": "user,manager,admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "1.Auth",
    "name": "GetAuthLogin"
  },
  {
    "type": "post",
    "url": "/auth/login",
    "title": "2. Login user",
    "version": "1.0.0",
    "group": "1.Auth",
    "permission": [
      {
        "name": "unauthorized"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\"status\": \"OK\",\n\t\"result\": {\n\t\t\"access_token\": \"yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\",\n\t\t\"token_type\": \"Bearer\",\n\t\t\"expires_at\": \"2021-08-28 16:29:41\"\n\t}\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.access_token",
            "description": "<p>User access token</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.token_type",
            "description": "<p>Token type</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.expires_at",
            "description": "<p>Token expiration date</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "1.Auth",
    "name": "PostAuthLogin"
  },
  {
    "type": "post",
    "url": "/auth/signup",
    "title": "1. Sign up a new user",
    "version": "1.0.0",
    "group": "1.Auth",
    "permission": [
      {
        "name": "unauthorized"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>User full name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password_confirmation",
            "description": "<p>Password confirmation</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "preferred_working_hour_per_day",
            "description": "<p>User setting <em>preferred working hour per day</em></p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "1.Auth",
    "name": "PostAuthSignup"
  },
  {
    "type": "delete",
    "url": "/user/:id",
    "title": "5. Delete a user",
    "version": "1.0.0",
    "group": "2.User",
    "permission": [
      {
        "name": "admin, manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The user ID.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "2.User",
    "name": "DeleteUserId"
  },
  {
    "type": "get",
    "url": "/user",
    "title": "1. User all users",
    "version": "1.0.0",
    "group": "2.User",
    "permission": [
      {
        "name": "admin, manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\"status\": \"OK\",\n\t\"result\": [\n\t\t{\n\t\t    \"id\": 1,\n\t\t    \"name\": \"User One\",\n\t\t    \"email\": \"user.one@test.com\",\n\t\t    \"role\": \"user\"\n\t\t}\n\t]\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.id",
            "description": "<p>User ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.name",
            "description": "<p>User full name</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.email",
            "description": "<p>User email</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.role",
            "description": "<p>User role</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "2.User",
    "name": "GetUser"
  },
  {
    "type": "get",
    "url": "/user/:id",
    "title": "2. Get user data",
    "version": "1.0.0",
    "group": "2.User",
    "permission": [
      {
        "name": "user, admin, manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>User ID or specified constant <code>me</code>.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\"status\": \"OK\",\n\t\"result\": {\n\t\t\"name\": \"User One\",\n\t\t\"email\": \"user.one@test.com\",\n\t\t\"role\": \"user\",\n\t\t\"preferred_working_hour_per_day\": 10\n\t}\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.name",
            "description": "<p>User full name</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.email",
            "description": "<p>User email</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.role",
            "description": "<p>User role</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.preferred_working_hour_per_day",
            "description": "<p>User setting <em>preferred working hour per day</em></p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "2.User",
    "name": "GetUserId"
  },
  {
    "type": "post",
    "url": "/user",
    "title": "3. Create a new user",
    "version": "1.0.0",
    "group": "2.User",
    "permission": [
      {
        "name": "admin,manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>User full name</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>User email</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>User password</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password_confirmation",
            "description": "<p>Password confirmation</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "preferred_working_hour_per_day",
            "description": "<p>User setting <em>preferred working hour per day</em></p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "user",
              "manager",
              "admin"
            ],
            "optional": true,
            "field": "role",
            "description": "<p>User role (only user with admin role can set a user role)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/AuthController.php",
    "groupTitle": "2.User",
    "name": "PostUser"
  },
  {
    "type": "put",
    "url": "/user/:id",
    "title": "4. Update user data",
    "version": "1.0.0",
    "group": "2.User",
    "permission": [
      {
        "name": "user, admin, manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>The User ID or specified constant <code>me</code>.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Full name of the user.</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "email",
            "description": "<p>User email (only users with admin/manager roles can edit email)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "allowedValues": [
              "user",
              "manager",
              "admin"
            ],
            "optional": true,
            "field": "role",
            "description": "<p>User role (only user with admin role can edit role)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "preferred_working_hour_per_day",
            "description": "<p>User setting <em>preferred working hour per day</em></p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/UserController.php",
    "groupTitle": "2.User",
    "name": "PutUserId"
  },
  {
    "type": "delete",
    "url": "/tasks/:id",
    "title": "5. Delete a task",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "admin, manager"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>The task ID.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "DeleteTasksId"
  },
  {
    "type": "get",
    "url": "/tasks",
    "title": "1. Get user tasks",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "user,admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_from",
            "description": "<p>Filter: date from</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_to",
            "description": "<p>Filter: date to</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "user_id",
            "description": "<p>Get all tasks using user ID (only user with admin role can use the param)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n     \"status\": \"OK\",\n     \"result\": {\n         \"tasks\": {\n             \"2020-08-26\": {\n                 \"tasks\": [\n                     {\n                         \"id\": 1,\n                         \"title\": \"Task title\",\n                         \"description\": \"Task description\",\n                         \"date\": \"2020-08-26\",\n                         \"duration\": 60\n                     }\n                 ],\n                 \"total_duration\": 60,\n                 \"covered_day_hours\": true\n             }\n         }\n         \"total_duration\": 120\n     }\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "result.tasks",
            "description": "<p>Tasks array indexed by date</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result.tasks.date",
            "description": "<p>Tasks group</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.tasks.date.tasks.id",
            "description": "<p>Task ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.tasks.date.tasks.title",
            "description": "<p>Task title</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.tasks.date.tasks.description",
            "description": "<p>Task description</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.tasks.date.tasks.date",
            "description": "<p>Date of the task</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.tasks.date.tasks.duration",
            "description": "<p>Duration of the task</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.tasks.date.total_duration",
            "description": "<p>Total duration of group of tasks</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.tasks.date.covered_day_hours",
            "description": "<p>Is date tasks duration covered preferred working hours</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.total_duration",
            "description": "<p>Total duration of all tasks</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "GetTasks"
  },
  {
    "type": "get",
    "url": "/tasks/export",
    "title": "6. Export user tasks",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "user, admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_from",
            "description": "<p>Filter: date from</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_to",
            "description": "<p>Filter: date to</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "user_id",
            "description": "<p>Get all tasks using user ID (only user with admin role can use the param)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\nThe server will generate HTML file",
          "type": "html"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "GetTasksExport"
  },
  {
    "type": "get",
    "url": "/tasks/:id",
    "title": "2. Get the task",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "user,admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "id",
            "description": "<p>Task ID</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\"status\": \"OK\",\n\t\"result\": {\n\t\t\"id\": 1,\n\t\t\"title\": \"Task title\",\n\t\t\"description\": \"Task description\",\n\t\t\"date\": \"2020-08-26\",\n\t\t\"duration\": 60\n\t}\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.title",
            "description": "<p>Task title</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.description",
            "description": "<p>Task description</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.date",
            "description": "<p>Date of the task</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "result.duration",
            "description": "<p>Duration of the task</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "GetTasksId"
  },
  {
    "type": "post",
    "url": "/tasks",
    "title": "3. Create a new task",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "user, admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Task title</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Task description</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date",
            "description": "<p>Date</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "duration",
            "description": "<p>Duration of the task</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": true,
            "field": "user_id",
            "description": "<p>User ID (only user with admin role can use the param to create a task for a user)</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "PostTasks"
  },
  {
    "type": "put",
    "url": "/tasks/:id",
    "title": "4. Update a task",
    "version": "1.0.0",
    "group": "3.Task",
    "permission": [
      {
        "name": "user, admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Task title</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Task description</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date",
            "description": "<p>Date</p>"
          },
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "duration",
            "description": "<p>Duration of the task</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 204 No Content",
          "type": "json"
        }
      ]
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/TaskController.php",
    "groupTitle": "3.Task",
    "name": "PutTasksId"
  },
  {
    "type": "get",
    "url": "/roles",
    "title": "1. User all roles",
    "version": "1.0.0",
    "group": "4.Role",
    "permission": [
      {
        "name": "admin"
      }
    ],
    "header": {
      "fields": {
        "Authorization Headers": [
          {
            "group": "Authorization Headers",
            "type": "Bearer",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Access token</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"Authorization\": \"Bearer yJ0eXAiOiJKV1QiLCJhbGciOiJSU...\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "\tHTTP/1.1 200 OK\n\t{\n\t\t\"status\": \"OK\",\n\t\t\"result\": [\n\t\t\t{\n\t\t\t    \"id\": 1,\n\t\t\t    \"name\": \"user\"\n\t\t\t},\n\t\t\t{\n\t\t\t    \"id\": 2,\n\t\t\t    \"name\": \"manager\"\n\t\t\t}\n         ...\n\t\t]\n\t}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.id",
            "description": "<p>Role ID</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result.name",
            "description": "<p>Role name</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/RoleController.php",
    "groupTitle": "4.Role",
    "name": "GetRoles"
  },
  {
    "type": "get",
    "url": "/status",
    "title": "1. API Status check",
    "version": "1.0.0",
    "group": "5.Common",
    "permission": [
      {
        "name": "unauthorized"
      }
    ],
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n\t\"status\": \"OK\",\n\t\"result\": []\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "status",
            "description": "<p>OK</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "result",
            "description": "<p>Result data</p>"
          }
        ]
      }
    },
    "filename": "../backend/app/Http/Controllers/Api/V1/StatusController.php",
    "groupTitle": "5.Common",
    "name": "GetStatus"
  }
] });
