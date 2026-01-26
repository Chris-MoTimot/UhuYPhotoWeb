import express from 'express';
import { getCommentsByPost, createComment, deleteComment } from '../controller/CommentController.js';
import { verifyToken } from '../middleware/AuthMiddleware.js';

const router = express.Router();

// Public routes
router.get('/posts/:postId/comments', getCommentsByPost);

// Protected routes
router.post('/posts/:postId/comments', verifyToken, createComment);
router.delete('/comments/:id', verifyToken, deleteComment);

export default router;