import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import db from './config/Database.js';
import path from 'path';
import { fileURLToPath } from 'url';
import AuthRoute from './routes/AuthRoute.js';
import PostRoute from './routes/PostRoute.js';
import CategoryRoute from './routes/CategoryRoute.js';
import CommentRoute from './routes/CommentRoute.js';
import User from './model/UserModel.js';
import Category from './model/CategoryModel.js';
import Post from './model/PostModel.js';
import Comment from './model/CommentModel.js';

dotenv.config();

const app = express();
const PORT = process.env.PORT || 5000;

// Get __dirname in ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Middleware
app.use(cors({
  origin: '*', // Untuk production, ganti dengan domain spesifik
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Serve static files (uploaded images)
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Routes
app.use('/api/auth', AuthRoute);
app.use('/api', PostRoute);
app.use('/api', CategoryRoute);
app.use('/api', CommentRoute);

// Root endpoint
app.get('/', (req, res) => {
  res.json({
    success: true,
    message: 'Pinterest Clone API - Backend is running',
    version: '1.0.0',
    endpoints: {
      auth: {
        register: 'POST /api/auth/register',
        login: 'POST /api/auth/login',
        profile: 'GET /api/auth/profile',
        updateProfile: 'PUT /api/auth/profile'
      },
      posts: {
        getAll: 'GET /api/posts',
        getById: 'GET /api/posts/:id',
        getUserPosts: 'GET /api/posts/user/:userId',
        create: 'POST /api/posts',
        update: 'PUT /api/posts/:id',
        delete: 'DELETE /api/posts/:id',
        like: 'POST /api/posts/:id/like'
      },
      categories: {
        getAll: 'GET /api/categories',
        getById: 'GET /api/categories/:id',
        create: 'POST /api/categories (Admin)',
        update: 'PUT /api/categories/:id (Admin)',
        delete: 'DELETE /api/categories/:id (Admin)'
      },
      comments: {
        getByPost: 'GET /api/posts/:postId/comments',
        create: 'POST /api/posts/:postId/comments',
        delete: 'DELETE /api/comments/:id'
      }
    }
  });
});

app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({
    success: false,
    message: err.message || 'Terjadi kesalahan pada server'
  });
});

const startServer = async () => {
  try {
    await db.authenticate();
    console.log('Database connection has been established successfully.');

    await db.sync({ alter: true });
    console.log('All models were synchronized successfully.');

    app.listen(PORT, () => {
      console.log(`Server is running on port ${PORT}`);
      console.log(`API Documentation: http://localhost:${PORT}`);
    });
  } catch (error) {
    console.error('❌ Unable to connect to the database:', error);
    process.exit(1);
  }
};

startServer();