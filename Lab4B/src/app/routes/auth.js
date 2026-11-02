/*
 * This module implements Google OAuth authentication. Most of it is
 * implemented for you. Fill in the parts labeled TODO: according to the lab
 * instructions. For the rest, read the comments to see how it functions.
 */

// Import modules
const router = require('express').Router();
const passport = require('passport');
const googleStrategy = require('passport-google-oauth20').Strategy;

// When you call auth/google it accepts a 'returnTo' query string argument
// with a URL to which browser should be redirected once authentication
// completes. We store that value in a cookie so that it can be retrieved
// upon successful login. This is the name of the cookie.
const returnToCookieName = 'authReturnTo';

// Configure passport with the Google strategy
// (This is not exclusive; other strategies could also be included.)
passport.use(new googleStrategy({
        clientID: process.env.GOOGLE_CLIENT_ID,
        clientSecret: process.env.GOOGLE_CLIENT_SECRET,
        callbackURL: '/api/v1/auth/google/callback' // This must match the callback path below
    },

    // This inline function processes the Google response and makes it into the user record that is stored in the session token cookie
    function(accessToken, refreshToken, profile, cb) {
        // Uncomment this to view the raw response from Google on the console
        //console.log(profile); 

        //TODO: The user record should have the following properties: id, userName, and email
        // Retrieve the appropriate values from the 'profile' variable and set them here in place of
        // these constant values.
        const user = {id: profile.id, userName: profile.name.givenName + profile.name.familyName, email: profile._json.email };

        // Return the value in the callback
        cb(null, user); // First argument is the error (if any), second argument is the newly created user record that will go into the session token
    }
));

// Route that delegates authentication to Google
router.get('/google',
    // A route can be assigned multiple steps. In this case, we set the
    // returnTo cookie to the corresponding query string value (if any)
    // and then pass control to the next step in the chain.
    (req, res, next) => {
        const returnTo = req.query.returnTo;
        //console.log('returnTo=%s', returnTo);
        if (returnTo) {
            res.cookie(returnToCookieName, returnTo);
        }
        else {
            res.clearCookie(returnToCookieName); // Make sure there's not a leftover cookie
        }
        next();
    },

    // This is the next step in the route. It delegates handling to the
    // the Passport-Google authenticator and indicates the scope of access that
    // is being requested.
    passport.authenticate('google', { scope: ['https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'] })
);

// Route that receives the authentication payload back from Google
router.get('/google/callback',
    // First step, let the Passport-Google authenticator process the response
    passport.authenticate('google'),
    
    // Second step, finish by redirecting the browser to an appropriate page.
    (req, res) => {
        const returnTo = req.cookies[returnToCookieName];
        if (returnTo) {
            res.clearCookie(returnToCookieName);
        }
        else {
            returnTo = '/api/v1/status';
        }
        res.redirect(returnTo);
    }
);

// Function that logs the user out by clearing the session
function logout(req, res) {
    req.logout(() => {
        const returnTo = req.query.returnTo;
        if (returnTo) {
            res.redirect(returnTo);
        }
        else {
            res.json({ message: "Logged out."});
        }
    })
}

// Assign logout to both GET and POST
router.get('/logout', logout);
router.post('/logout', logout);

module.exports = router;