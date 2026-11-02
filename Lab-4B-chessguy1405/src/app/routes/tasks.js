/*
 * In this module you will implement the CRUD methods for Creating, Reading,
 * Updating, and Deleting tasks.
 *
 * One of the methods, Read, has been implemented for you as an example.
 * 
 */

const express = require('express');
const router = express.Router();

// TODO: Import your task model here
const Task = require('../models/taskModel');

/**
 * Read: Return one task with the task's id specified in the path
 */
router.get(`/:id`, async (req, res) => {
	try {
		const task = await Task.findById(req.params.id);
		if (!task) {
			res.status(404).send(`Task with ID ${req.params.id} does not exist.`);
		}
		else {
			res.status(200).send(task);
		}
	}
	catch (error) {
		console.error(error);
		res.status(500).send(`Something went wrong.`);
	}
});

/**
 * Create: Post one task with a body containing the text and date
 */
// TODO: Implement Create Task
// The Mongoose Model reference will be helpful: https://mongoosejs.com/docs/models.html
router.post(`/`, async (req, res) => {
	try {
		if (!req.user) {
			res.status(401).send("Unauthorized. Please log in.");
		}
		else {
			if (!(req.body.text && req.body.date)) {
				res.status(400).send("Bad request");
			}
			else {
				const text = req.body.text;
				const date = req.body.date;
				let newTask = new Task();
				newTask.text = text;
				newTask.date = date;
				newTask.done = false;
				newTask.userId = req.user.id;
				let addedTask = await newTask.save();
				res.status(201).send(addedTask);
			}
		}
	}
	catch (error) {
		console.error(error);
		res.status(500).send("Something went wrong.");
	}
});

/**
 * ReadAll: Get all tasks
 */
// TODO: Implement Read All Tasks
router.get(`/`, async (req, res) => {
	try {
		if (req.user) {
			const tasks = await Task.find({userId: req.user.id});
			res.status(200).send(tasks);
		}
		else {
			res.status(401).send("Unauthorized. Please log in.");
		}
	}
	catch (error) {
		console.error(error);
		res.status(500).send("Something went wrong.");
	}
});

/**
 * Update: Put one task with the task's id specified in the path
 */
// TODO: Implement Update Task
router.put(`/:id`, async (req, res) => {
	try {
		if (!req.user) {
			res.status(401).send("Unauthorized. Please log in");
		}
		else {
			const foundTask = await Task.find({userId: req.user.id, _id: req.params.id});
			if (!foundTask) {
				res.status(404).send(`Task with ID ${req.params.id} does not exist.`);
			} else {
				if (!req.body.done) {
					res.status(400).send("Bad request");
				}
				else {
					let task = await Task.findByIdAndUpdate(req.params.id, {done: req.body.done});
					const updatedTask = await Task.find({_id: req.params.id});
					res.status(200).send(updatedTask);
				}
			}
		}
	}
	catch (error) {
		console.error(error);
		res.status(500).send("Something went wrong");
	}
});

/**
 * Delete: Delete one task with the task's id specified in the path
 */
// TODO: Implement Delete Task
router.delete(`/:id`, async (req, res) => {
	try {
		if (!req.user) {
			res.status(401).send("Unauthorized. Please log in.");
		}
		else {
			const task = await Task.find( {userId: req.user.id, _id: req.params.id} );
			if (!task) {
				res.status(404).send(`Task with ID ${req.params.id} does not exist.`)
			}
			else {
				Task.bulkWrite([{
					deleteOne: {
						filter: { userId: req.user.id, _id: req.params.id }
					}
				}]);
				res.sendStatus(200);
			}
		}
	}
	catch (error) {
		console.error(error);
		res.status(500).send("Something went wrong.");
	}
});

module.exports = router;