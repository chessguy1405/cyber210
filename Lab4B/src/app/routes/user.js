/*
 * This module should implement the 'user' endpoint. You can use the
 * examples from other such as 'auth.js' to help you implement it.
 */

const express = require('express');
const router = express.Router();

/*
* User: returns the id, username, and email of the currently logged in user
*/
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