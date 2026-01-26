import express from 'express';
import { 
  getAllPosts, 
  getPostById, 
  createPost, 
  updatePost, 
  deletePost,
  likePost,
  getUserPosts
} from '../controller/PostController.js';
import { verifyToken } from '../middleware/AuthMiddleware.js';
import { upload } from '../middleware/UploadMiddleware.js';

const router = express.Router();

// Public routes
router.get('/posts', getAllPosts);
router.get('/posts/:id', getPostById);
router.get('/posts/user/:userId', getUserPosts);

// Protect routes
router.post('/posts', verifyToken, upload.single('image'), createPost);
router.put('/posts/:id', verifyToken, upload.single('image'), updatePost);
router.delete('/posts/:id', verifyToken, deletePost);
router.post('/posts/:id/like', verifyToken, likePost);

export default router;