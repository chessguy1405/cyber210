/*
 * This module is the core of your express application. It routes requests to
 * handlers ("controllers" in the Model-View-Controller parlance). Routing for
 * 'auth' and 'status' are already in place. There is also a route handler for
 * static files in your 'public' folder. You will need to add routes for user
 * and tasks.
 * 
 * Be sure to require authentication where appropriate!
 */
const express = require('express');
const router = express.Router();

/**
 * This middleware function is used to require authentication before
 * calling the destination function.
 * 
 * Example:
 * router.use('/api/endpoint', authenticate, require('./routes/controller'));
 * 
 */
function authenticate(req, res, next) {
	if (req.user) next()
	else res.sendStatus(401)
}

// Load the routes
router.use('/api/v1/auth', require('./routes/auth'));
router.use('/api/v1/status', require('./routes/status'));
router.use('/api/v1/tasks', authenticate, require('./routes/tasks'));
router.use('/api/v1/user', authenticate, require('./routes/user'));

// This serves all '.html' and '.htm' files in your public directory
router.use(express.static('public', {
    dotfiles: 'ignore',
    extensions: ['html', 'htm']
}));

module.exports = router;
