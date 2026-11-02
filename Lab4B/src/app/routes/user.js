/*
 * This module should implement the 'user' endpoint. You can use the
 * examples from other such as 'auth.js' to help you implement it.
 */

// TODO: Implement the user module
const express = require('express');
const router = express.Router();


router.get(`/`, async (req, res) => {
    try {
        if (!req.user) {
            res.status(401).send("Unauthorized. Please log in.");
        }
        else {
            res.status(200).send(req.user);
        }
    }
    catch (error) {
        console.error(error);
        res.status(500).send("Something went wrong.");
    }
});

module.exports = router;