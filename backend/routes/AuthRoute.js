import express from 'express';
import { register, login, getProfile, updateProfile } from '../controller/AuthController.js';
import { verifyToken } from '../middleware/AuthMiddleware.js';

const router = express.Router();

// Public routes
router.post('/register', register);
router.post('/login', login);

//Protect routes
router.get('/profile', verifyToken, getProfile);
router.put('/profile', verifyToken, updateProfile);

export default router;