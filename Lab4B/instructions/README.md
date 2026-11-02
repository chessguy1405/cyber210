# Lab 4B  - Node.js RESTful API

## Overview

You've created your Todo app in two different frameworks so far:

1. Vanilla HTML, CSS, and JavaScript
2. PHP

In Lab 4A you created a unit test to verify a **RESTful API** that Writes and Reads Todo tasks. In this lab you will build that API and test it using your Lab 4A tester.

Among the advantages of using an API is that data functionality is detached from the front-end user interface (UI). That means that you can create a front-end on any device or any framework, and they can all communicate with the same database!

### Functionality

- Running API that handles RESTful HTTP calls to read and update a database
- Store data in a NoSQL Database hosted in the cloud (MongoDB hosted on Atlas)

### Concepts

- REST services
- HTTP Verbs
- Express and Express Middleware
- NPM (Node Package Manager)
- Cloud Services: Hosting, Databases, Authentication, etc.
- NoSQL Databases (MongoDB)
- OAuth

### Resources

- [NodeJs Install and Documentation](https://nodejs.org/en)
- [ExpressJS Guide](https://expressjs.com/en/guide/routing.html)
- [Mongoose MongoDB Access for Node.js](https://mongoosejs.com)

## Introduction

First you will set up cloud services; Google Authentication and MongoDB Atlas. Then you will initialize and configure your Node - NPM project, then you will build and test.

## Step 1: Set Up Cloud Services

Your API will use Google for authentication and MongoDB for your database. In both cases, these are remote services which relieve you from the burden of installing databases or implementing authentication services. You may have already set these up at the tail end of Lab 4A. If so, congratulations! If not, follow these instructions:

* [ITC 210: Set Up Google Authentication](https://byu-itc-210.github.io/GoogleAuthentication)
* [ITC 210: Create a MongoDB Database](https://byu-itc-210.github.io/MongoDbSetup)

Upon completing these steps you will have a Google Authentication Client ID, a Google Authentication Client Secret, and a MongoDB Connection string. Make sure you save those values. Later you will paste them into your **.env** file.

## Step 2: Configure and Validate Your Application

You should already have Node.js installed on your computer from Lab 4A. If not, go back to the instructions for that lab and set it up. Make sure you install the LTS (Long-term support) version of Node. You can check that it's already installed by typing `node --version` into a command prompt. If it is installed, it will print the version; otherwise you will get an error message.

### Use the Template

Clone the Lab-4B repo onto your computer and open it in VSCode.

In Lab 4A we had you create your project from scratch by using `npm init` and then installing the packages your needed by your project with `npm install`. This time we have pre-built your **package.json** file which lists all of the required packages. Nevertheless you still need to install all of the needed packages. Since they are already listed in **package.json** this is a quick task.

1. Open a terminal and change to the **src** directory in your project.
2. Enter the following command:

```sh
npm install
```

This will download all of the packages your project needs. They include the following:

* [ExpressJs](https://expressjs.com) - A web application framework for Node.js
* [Mongoose](https://mongoosejs.com) - A client framework for accessing MongoDB
* [Passport](https://www.passportjs.org) - Authentication services for Express
* [passport-google-oauth20](https://www.npmjs.com/package/passport-google-oauth20) - Google OAuth 2.0 provider for Passport authentication
* [session-cookie](https://www.npmjs.com/package/session-cookie) - Simple session management for Express using cookies
* [dotenv](https://www.npmjs.com/package/dotenv) - Reads configuration values from your .env file
* [cookie-parser](https://www.npmjs.com/package/cookie-parser) and [cookies](https://www.npmjs.com/package/cookies) - Provide convenient access for reading and writing cookies.

### Configure your application

By now you should be familiar with **.env** files. Like other projects, you need to keep track of configuration and secrets.

Save a copy of the provided **.env.example** file to **.env**.

Edit the file as follows:
* Paste in your **MONGODB_CONNECTION_STRING**. You will have obtained it when you created your MongoDB Atlas database.
* Paste in your **GOOGLE_CLIENT_ID** and your **GOOGLE_CLIENT_SECRET**. As with MongoDB, you will have obtained these values when you followed the Google Authentication setup previously.
* Make up a **SESSION_SECRET**. This will be used to digitally sign your cookies. A random sequence of letters and numbers is a good choice.

> If you set up the services but lost these values somehow, you can recover the Google values by accessing the [Google Auth Clients](https://console.cloud.google.com/auth/clients) page. For MongoDB you will need to log into [MongoDB Atlas](https://cloud.mongodb.com/). There you can get the connection string. Insert into the string the proper username, password, and database name according to the [MongoDB Instructions](https://byu-itc-210.github.io/MongoDbSetup) that we gave you. If you don't have the password you will need to change it to a known value.

### Use CallAPI to Validate your configuration

Like the APIs we gave you to test, we have included a copy of CallAPI with your server. With configuration in place, you can already verify basic functionality.

1. In your terminal, on the **.src** directory, enter: `node server.js`
2. Check for success and error messages. For example, it will report if it has successfully connected to the database.
    - If you got an error, press `Ctrl-C` to exit the app, go back and check the configuration in your **.env** file.
    - Else, if it says **MongoDb Connected** and **Listening on port 1337** then things are working. Proceed.
3. Browse to [http://localhost:1337/callapi](http://localhost:1337/callapi)
4. Click the **Status** template and click **Send**
    - Look for `"dbState": "Connected"` to verify that the database is connected
    - Since you haven't logged in, it should report `"user": "(none)"`
5. At your terminal, press `Ctrl-C` to exit your server.

Each time you make a change to your server code you must stop and restart the server for your changes to take effect.

Now is a good time to commit and push.

## Step 3: Implement Authentication

When you publish an API to the world wide web, you must secure it unless you want potentially malicious actors flooding it with requests. For this project, you will use Google Authentication. To connect with it, you use three packages that integrate with the [Express Web Application Server](https://expressjs.com): [Passport](https://www.passportjs.org), [passport-google-oauth20](https://www.passportjs.org/packages/passport-google-oauth20) and [cookie-session](https://www.npmjs.com/package/session-cookie).

Since getting OAuth authentication set up is complicated, we've done much of the work for you. But we don't want you to miss out entirely. Please examine the following code:

In **server.js** look at the sections titled "**Add Passport authentication service to Express**" and "**Set up session management for Express**". The comments and code together show you how to load and integrate "**Passport**" and "**session-cookie**". Take note of how user information is (minimally) converted to and from the cookie form. In more complex scenarios the details might be saved to and retrieved form a database. Also note how long a cookie lasts before expiration.

The **app/routes/auth.js** module is where the authentication endpoints are located. It has four parts. The first part loads and configures **passport-google-oauth20**. That contains a **TODO** note that we will come back to. The second part handles requests to `/api/v1/auth/google`. It prepares a URL query string payload and redirects to Google authentication for the user to log in. The third part accepts an authentication token back from Google. The fourth part handles logout.

### Locate the data you need

Your task is to translate from the **profile** that Google sends back into the three properties that your application needs:
* id
* userName
* email

To help with that do the following:

1. In **app/routes/auth.js**, uncomment the line that says `console.log(profile);`. When you log in with Google, this will cause it to write the whole Google profile out to the console.
2. Start your server at the terminal: `node server.js`
3. Browse to [http://localhost:1337/callapi](http://localhost:1337/callapi)
4. Click the **Login** button.
5. Log into Google and let it return to your server. (The browser will return to **CallAPI** again.)
6. Look at the data dump you got on your terminal console and identify where to get the values you need. For example, **id** could be found at either `profile.id` or `profile._json.sub`; both have the same value.
7. Press `Ctrl-C` to exit the server.

### Set the correct user data in the session

1. Return to **app/routes/auth.js**
2. Put the comment back on `console.log(profile);` to keep your server from being too noisy.
3. At the **TODO** comment fix the code to set the proper `id`, `userName`, and `email` values which you should obtain from the `profile` object. The correct values should replace the placeholders you see there.

### Test your update

1. Start your server at the terminal: `node server.js`
2. Browse to [http://localhost:1337/callapi](http://localhost:1337/callapi)
3. Click the **Login** button.
4. Log into Google and let it return to your server. (It will show **CallAPI** again.)
5. Click the **Status** template and click **Send**
6. See if the status report shows your correct user information.

Also try the **Logout** template and check the status to make sure that logout clears your user information.

Now is a good time to commit and push.

## Step 4: Implement Your Model

In the Model-View-Controller design pattern, you create a **Model** to represent each data record to be stored in the database. The model also insulates you from the intricacies of dealing directly with the database and translating between its representation of data to how your programming language represents the same data.

Our application has just one model - that of a **Task**. Here, you will create a **JavaScript Class**, through the use of the Mongoose `Schema` class and `model()` functions.

1. Open the `taskModel.js` file in the `app/models` folder
2. [Use the Mongoose documentation](https://mongoosejs.com/docs/api/schema.html#Schema()) to create a Schema for a Task:
    > Note: We have already taken care of `require('mongoose')` and obtaining the **Schema** class.
 
    - Give your Task model the following properties:

        | Property | Type      |
        | -------- | --------- |
        | `UserId` | `String`  |
        | `Text`   | `String`  |
        | `Done`   | `Boolean` |
        | `Date`   | `String`  |

    - In the options portion, set the collection name to `Tasks`. This references the collection you created when setting up the database in Step 1.

3. [Follow these Mongoose instructions](https://mongoosejs.com/docs/api/model.html#Model()) to create a **Model** from your schema.
    - It is basically just calling `mongoose.model()` on your schema.
4. Assign your model to `module.exports` so that it can be used by other modules.

We can't test this code until you implement your CRUD functions. Nevertheless, now is a good time to commit and push.

## Step 5: Review the API

In the next steps, you will implement the API. So, now is a good time to review it. Here is the table from Lab 4A:

| Operation            | HTTP Method | Endpoint                 | Expected Response      | Headers                      | Body                   |
|----------------------|-------------|--------------------------|------------------------|------------------------------|------------------------|
| Login                | GET         | `api/v1/auth/google?returnto=<return_url>` | 302 Redirect to the Google Login page | none | none          |
| **C**reate a task    | POST        | `api/v1/tasks`           | Newly created task     | Content-Type, Cookie, Origin | `Text, Date, (Done)`   |
| **R**ead all tasks   | GET         | `api/v1/tasks`           | List of tasks          | Cookie, Origin               | none                   |
| **R**ead one task    | GET         | `api/v1/tasks/<task_id>` | Single task            | Cookie, Origin               | none                   |
| **U**pdate a task    | PUT         | `api/v1/tasks/<task_id>` | Updated task           | Content-Type, Cookie, Origin | `Done, (Text), (Date)` |
| **D**elete a task    | DELETE      | `api/v1/tasks/<task_id>` | 200 OK                 | Cookie, Origin               | none                   |
| **D**elete all tasks | DELETE      | `api/v1/tasks/all`       | 200 OK                 | Cookie, Origin               | none                   |
| **R**ead user info   | GET         | `api/v1/user`            | Logged in user info    | Cookie, Origin               | none                   |
| Get server status    | GET         | `api/v1/status`          | Server and user status | Cookie, Origin               | none                   |

## Step 6: Create The Routes and Endpoints

**Endpoint** is a fancy way of saying "this URL will handle HTTP requests for this kind of data". You will see endpoints referred to as the second half of a URL, for instance, you will create an endpoint to handle all of the CRUD operations for our list of **tasks**: `/api/v1/tasks`. Everything before that text will be the actual address of our server. So, if you have a server at `example.com` on port `1337`, you could make a GET request to the endpoint by typing this into the browser:

```
https://example.com:1337/api/v1/tasks
```

> Note: It is good practice to version your API (i.e. the `v1` in `/api/v1/endpoint`) so that if you ever change the endpoints, it doesn't break any front-end applications that are using your API (i.e. instead of changing `/api/v1/endpoint`, you should create a new one: `/api/v2/endpoint`).

For our endpoints, you will implement an express **controller** for each of the CRUD operations. You will need one Create function, two Read functions (one to read all tasks and one to read a single task), an Update function and a Delete function.

> These functions are the **Controllers** from the **Model-View-Controller** design pattern. Each controller handles a particular request. In a typical **Read** request, the controller would use the appropriate **Model** class to retrieve a representation of the data from the database. Then it would call a **View** function to render that model for the user to view. When an API like this one is built according to the **Model-View-Controller** pattern the **View** is usually trivial. For example, the **Read** controller that we implemented for you finishes by calling `send(task);`. In this case, `send()` is the **View**. It converts the model instance into JSON and transmits it to the client. A UI view would convert the model into some nice-looking HTML.

| Operation | Express Function |
| --------- | ---------------- |
| Create    | `.post()`        |
| Read      | `.get()`         |
| Update    | `.put()`         |
| Delete    | `.delete()`      |

You will fill in the **TODO** prompts in `app/routes/tasks.js` to implement each of these endpoints. The first route is already implemented. It gets a single task by ID. Refer to it as an example as you create the other four endpoints.

All of your interactions with the database will be through the Mongoose Task model that you created in Step 4. The [Mongoose Model guide](https://mongoosejs.com/docs/models.html) and [Mongoose Model reference](https://mongoosejs.com/docs/api/model.html) will help you know what methods to call on the model.

As an example, to create a new task you would "new" a task (e.g. `let task = new Task();`). Then you would set each of the properties (e.g. `task.text = 'Buy Ice Cream';`). Then you would save the task to the database (e.g. `let addedTask = await task.save();`).

Take a look at the documentation for [Model.prototype.save()](https://mongoosejs.com/docs/api/model.html#Model.prototype.save()). The "prototype" term means that this is a method on a model *object*, like a task that you create. Also notice that it returns a promise. That means that it doesn't work instantly. Rather, it returns a [promise](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise) to notify you when the action is complete. As we discussed in class, an [async](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/async_function) function can use `await` to suspend operations and let other tasks run until the action is complete. In this case, the **promise** returned by `save()` will fulfill with the object that was saved to the database including the `_id` property that was assigned by the database. So `let addedTask = await task.save();` requests that the task be added to the database. The `await` keyword suspends operation of this function and lets the server process other stuff until the database operation is complete. Then, when the **promise** is fulfilled, the newly created task is assigned to `addedTask` and the function resumes with the next line of code.

A [promise](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Promise) can also be rejected if an error occurs. To capture that condition, wrap the call in a [try ... catch](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/try...catch) block. If you end the block with `catch(error)` then you can report the error to the console.

Now look at the documentation for [Model.find()](https://mongoosejs.com/docs/api/model.html#Model.find()). It is *not* a *prototype* method. That means that you call it directly on the **Model** object. Like `save()` it returns a promise. And to find something you pass a JavaScript object with the properties you want to match. So, to find all tasks that belong to a particular user: `let tasks = Task.find({userId: req.user.id});`. Of course, you can specify multiple properties that should be matched (e.g. `let tasks = Task.find({text: 'Fly', date: '2025-07-15'});`).

Follow these steps to implement the additional endpoints:

1. In the file `app/routes/tasks.js`, require (import) your Mongoose **Task** model.
    - Since the **models** folder is at the same level as the **routes** folder your path will be `../models/taskModel`. So, the line is this: `const Task = require('../models/taskModel');`

2. Add 4 new routes:
    - The first route is already implemented. It gets a single task by ID. Use it as a pattern for the others.
    - `.post('/')`
        - Create a new `Task` object using the `req.body` and `req.user` objects as sources of data.
        - Make an `await` request using Mongoose to `.save()` the new `task`, and store the result in a variable.
        - Use the `res` parameter to `.send()` the result back to the requester with a `201` status (Created).
        - Surround the process in a `try ... catch` statement, to catch and report any errors.
        - If there are errors, log them to the console with `console.error()` and use the `res` object to send a `500` error (Server Error).
    - `.get('/')`
        - Use the `Task` class to `.find()` all of the tasks for the authenticated user.
        - Use the `res` object to `.send()` the tasks back to the client.
    - `.put('/:id')`
        - Use the `Task` class to find, update and `.save()` the task.
        - Use the `res` object to `.send()` the task back to the client.
    - `.delete('/:id')`
        - Use the `Task` class to `deleteOne()` the task.
        - Use the `res` object to `.send()` a message back to the client.

    > [There are plenty of generally accepted response codes available](https://developer.mozilla.org/en-US/docs/Web/HTTP/Status) to use in your app. The most important thing is that they make sense to anyone who wants to use your API. Successful actions should have a response code in the 200 range. Errors caused by incorrect requests should have response codes in the 400 range while errors on the server have response codes in the 500 range.

3. In `app/index.js`, just after the routes for `auth` and `status`, add a route for `models.tasks`.
    - Make sure to include a version number in the path. E.g. `/api/v1/tasks`
    - Add the `authenticate` method in the chain of calls for this endpoint. Otherwise callers could use the endpoint without logging in.
        - You insert `authenticate` between the route and the `require`. Something like this: `router.use('/api/v1/beep', authenticate, require('./routes/beeper'));`
        - `authenticate` is not necessary for the `auth` and `status` endpoints because they are intentionally accessible regardless of whether you are logged in.

4. Use **CallAPI** to test your endpoints
    - Start your server: `node server.js`
    - Browse to [http://localhost:1337/callapi](http://localhost:1337/callapi)
    - Test each function

Now is a good time to commit - push.

## Step 7: Implement the Get User Call

The API endpoint `api/v1/user` should return user user information: id, userName, and email. Use what you have learned from building the Task CRUD functions to implement this one endpoint. Test it using **CallAPI**.

## Step 8: Testing

Use your Lab 4A unit tests to test and fine tune your code. You will have to login to your application and get the **Cookie** and **Origin** from from the **status** call. Then open your Lab 4A **.env** file and update the values there.

In order to get all tests to pass, your CRUD endpoints will have to do some input validation. For example, when POSTing an item you should check that both `text` and `date` have values and return an appropriate error if they do not. You may also have to check for error returns from database functions and address other details.

Check for security enforcement. For example, one user should not be able to retrieve, delete, or update tasks for another user. This will require that all calls check for a **userId** match.

When all tests have passed, you know you have a working API!

## Step 9: Hosting

Once your code is pushed to your GitHub repo, you will need to do the following to prepare your production environment:
  
1. Google Developer Console
    - Your server should already have a domain name. Add your public domain to the authorized Redirect URIs in the Google Developer Console `https://<your_domain.itc210.net>:1337/api/v1/auth/google/callback`

3. Update Server Firewall rules
    - In the AWS console, go to your `Instance summary` and select the `Security` tab
    - Under the heading `Security Groups` click on the link then click on `Edit inbound rules`
    - Verify that a rule for HTTPS exists with its `source` set to `Anywhere-IPv4`
    - Add another rule where the type is `Custom TCP`, the `Port range` is `1337` and the `source` is `Anywhere-IPv4`
    - Select `Save rules`
    - This rule will let your back-end server operate on port `1337` while your front-end server (coming up in Lab 5) operates on the default ports 80 and 443.

4. Server Set Up
    - Install Node.js
  

        - You need to install node.js from a Linux binary file. DO NOT install it through the `apt` package manager. Ubuntu's repositories contain a very old version of node which will not work with your code.
        - Visit The [Node.js site](https://nodejs.org/en/download) and note the latest LTS version available (i.e. vX.X.X).
        - Run the following commands on your live server, replacing `<Version>` with the latest LTS version available:
            ```bash
            cd ~
            curl -o node.tar.xz https://nodejs.org/dist/<Version>/node-<Version>-linux-x64.tar.xz
            tar xf node.tar.xz
            nano ~/.profile
            ```
        - Then, edit the `~/.profile` file with your favorite editor (e.g. `nano ~/.profile`) and add the following lines to the bottom of the file:
            ```bash
            # Node.js
            export NODEJS_HOME=/opt/node-<Version>-linux-x64/bin
            export PATH=$NODEJS_HOME:$PATH
            ```
        - We want your server to work with your custom domain. However, the SSL certificate is owned by root, so we need to change it to be owned by the `ubuntu` user so node can use it:
            ```sh
            sudo chown ubuntu -R /etc/letsencrypt
            ```
        - Save the file and exit, then run the `. ~/.profile` command
            > Note: When this lab was created, the latest LTS version was v20.11.1. As of the time of writing, the current LTS version of Node.js is v24.11.0. As demonstrated here, the latest version can change. When working with outdated documentation, make sure to adjust commands according to what version you downloaded.

    - Clone your repo onto the server into your home directory.
    
    - Inside your repo, run `npm install` to install all dependencies listed in your **package.json** file.

    - Create a **.env** file in the `src` folder of your project using your favorite Linux editor (e.g. `nano` or `vim`).

    - Add all of the variables from your development `.env` file to your production `.env`. Uncomment `DOMAIN` and set it to be `<your_domain.itc210.net>`. Set `PROTOCOL` to `https`

    - Try to start your Node.js application by running `npm start`

    - Test your API using **CallAPI** on the new server (`https://<your_domain.itc210.net>:1337/callapi`). Make sure you are using `https` and not `http` to access your API.

    - If everything is set up correctly, you should be able to check status, login, etc.

And just like that, you have deployed a beautiful RESTful API!!

# Node.js RESTful API Pass-off

## Pass-off Requirements

- [ ] 5 Points - First commit is on or before Friday
- [ ] 8 Points - Application is deployed to a live cloud server
- [ ] 4 Points - Source code is pushed to GitHub
- [ ] 9 Points - Secrets are protected:
  - `.env` file is not found on GitHub
  - There are no hard-coded secrets in `passport.js`
  - There are no insecure hard-coded strings that can be found in any commit in your GitHub repo (passwords, connection strings, etc)
- [ ] 14 Points - Site is secure:
  - User is unable to access `/api/v1/user` or `/api/v1/tasks` unless logged-in
  - User is able to access `/api/v1/user` and `/api/v1/tasks` after logging in
  - User can log in with Google
  - After logging in, there is a cookie called `it210_session`
- [ ] 20 points - API passes all unit tests from Lab 4A.
- [ ] 5 Points - Code is neat and tidy with appropriate comments when useful

> For extra credit to apply, all pass-off requirements need to be met

# Extra Credit

> Note: TAs cannot help you with extra credit!

- [ ] 8 Points - CallAPI has a template for DeleteAll but that call is not in the API or the spec. Implement DeleteAll. Make sure it only deletes tasks for the current user.

# Writeup Questions and Guidelines

- The UML diagram for this writeup can be any of the three types we have discussed in class: Activity Diagram, Class Diagram, or Sequence Diagram. Regardless of the type, it should support the text of your writeup.
- List three advantages to using a web API.
- What are the differences between these four HTTP methods: GET, POST, PUT, and DELETE? Which ones are idempotent?
