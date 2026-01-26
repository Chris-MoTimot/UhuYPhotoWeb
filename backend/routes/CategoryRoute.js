import express from 'express';
import { 
  getAllCategories, 
  getCategoryById, 
  createCategory, 
  updateCategory, 
  deleteCategory 
} from '../controller/CategoryController.js';
import { verifyToken, isAdmin } from '../middleware/AuthMiddleware.js';

const router = express.Router();

// Public routes
router.get('/categories', getAllCategories);
router.get('/categories/:id', getCategoryById);

// Protected routes (Admin only)
router.post('/categories', verifyToken, isAdmin, createCategory);
router.put('/categories/:id', verifyToken, isAdmin, updateCategory);
router.delete('/categories/:id', verifyToken, isAdmin, deleteCategory);

export default router; 