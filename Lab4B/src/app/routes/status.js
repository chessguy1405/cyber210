/*
 * This module is provided for you. It shows how to implement a single endpoint
 * and it returns status information to help you know when the database is
 * connected and you are logged in.
 */

const express = require(`express`)
const router = express.Router()
const mongoose = require('mongoose');

router.get('/', async (req, res) => {
    let info = {title: 'Status Report', user: '(none)'};
    if (req.user) {
        info.user = req.user;
        info.cookie = req.cookies[process.env.COOKIE_NAME];
    }
    info.origin = req.protocol + '://' + req.headers["host"];
    info.dbState = mongoose.connection.readyState == 1 ? 'Connected' : 'Not Connected';
    res.json(info);
});

module.exports = router;