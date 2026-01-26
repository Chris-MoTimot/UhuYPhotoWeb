import Post from '../model/PostModel.js';
import User from '../model/UserModel.js';
import Category from '../model/CategoryModel.js';
import Comment from '../model/CommentModel.js';

export const getAllPosts = async (req, res) => {
  try {
    const { page = 1, limit = 20, categoryId, search } = req.query;
    const offset = (page - 1) * limit;

    let whereClause = {};
    
    if (categoryId) {
      whereClause.categoryId = categoryId;
    }

    if (search) {
      whereClause[db.Sequelize.Op.or] = [
        { title: { [db.Sequelize.Op.like]: `%${search}%` } },
        { description: { [db.Sequelize.Op.like]: `%${search}%` } },
        { tags: { [db.Sequelize.Op.like]: `%${search}%` } }
      ];
    }

    const { count, rows } = await Post.findAndCountAll({
      where: whereClause,
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        },
        {
          model: Category,
          as: 'category',
          attributes: ['id', 'name', 'slug']
        }
      ],
      limit: parseInt(limit),
      offset: parseInt(offset),
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: {
        posts: rows,
        pagination: {
          total: count,
          page: parseInt(page),
          limit: parseInt(limit),
          totalPages: Math.ceil(count / limit)
        }
      }
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const getPostById = async (req, res) => {
  try {
    const { id } = req.params;

    const post = await Post.findByPk(id, {
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage', 'fullName']
        },
        {
          model: Category,
          as: 'category',
          attributes: ['id', 'name', 'slug']
        },
        {
          model: Comment,
          as: 'comments',
          include: [{
            model: User,
            as: 'user',
            attributes: ['id', 'username', 'profileImage']
          }],
          order: [['createdAt', 'DESC']]
        }
      ]
    });

    if (!post) {
      return res.status(404).json({ 
        success: false,
        message: 'Post tidak ditemukan' 
      });
    }

    // Increment views
    await post.increment('views');

    res.status(200).json({
      success: true,
      data: post
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const createPost = async (req, res) => {
  try {
    const { title, description, categoryId, tags } = req.body;
    const userId = req.user.id;

    if (!title || !req.file) {
      return res.status(400).json({ 
        success: false,
        message: 'Title dan gambar wajib diisi' 
      });
    }

    const imageUrl = `/uploads/${req.file.filename}`;

    const post = await Post.create({
      userId,
      categoryId: categoryId || null,
      title,
      description: description || '',
      imageUrl,
      tags: tags || ''
    });

    const newPost = await Post.findByPk(post.id, {
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        },
        {
          model: Category,
          as: 'category',
          attributes: ['id', 'name', 'slug']
        }
      ]
    });

    res.status(201).json({
      success: true,
      message: 'Post berhasil dibuat',
      data: newPost
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const updatePost = async (req, res) => {
  try {
    const { id } = req.params;
    const { title, description, categoryId, tags } = req.body;
    const userId = req.user.id;

    const post = await Post.findByPk(id);

    if (!post) {
      return res.status(404).json({ 
        success: false,
        message: 'Post tidak ditemukan' 
      });
    }

    // Cek apakah user adalah pemilik post
    if (post.userId !== userId && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false,
        message: 'Anda tidak memiliki akses untuk mengupdate post ini' 
      });
    }

    let imageUrl = post.imageUrl;
    if (req.file) {
      imageUrl = `/uploads/${req.file.filename}`;
    }

    await post.update({
      title: title || post.title,
      description: description || post.description,
      categoryId: categoryId || post.categoryId,
      tags: tags || post.tags,
      imageUrl
    });

    const updatedPost = await Post.findByPk(id, {
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        },
        {
          model: Category,
          as: 'category',
          attributes: ['id', 'name', 'slug']
        }
      ]
    });

    res.status(200).json({
      success: true,
      message: 'Post berhasil diupdate',
      data: updatedPost
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const deletePost = async (req, res) => {
  try {
    const { id } = req.params;
    const userId = req.user.id;

    const post = await Post.findByPk(id);

    if (!post) {
      return res.status(404).json({ 
        success: false,
        message: 'Post tidak ditemukan' 
      });
    }

    // Cek apakah user adalah pemilik post
    if (post.userId !== userId && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false,
        message: 'Anda tidak memiliki akses untuk menghapus post ini' 
      });
    }

    await post.destroy();

    res.status(200).json({
      success: true,
      message: 'Post berhasil dihapus'
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const likePost = async (req, res) => {
  try {
    const { id } = req.params;

    const post = await Post.findByPk(id);

    if (!post) {
      return res.status(404).json({ 
        success: false,
        message: 'Post tidak ditemukan' 
      });
    }

    await post.increment('likes');

    res.status(200).json({
      success: true,
      message: 'Post berhasil di-like',
      data: {
        likes: post.likes + 1
      }
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const getUserPosts = async (req, res) => {
  try {
    const { userId } = req.params;
    const { page = 1, limit = 20 } = req.query;
    const offset = (page - 1) * limit;

    const { count, rows } = await Post.findAndCountAll({
      where: { userId },
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        },
        {
          model: Category,
          as: 'category',
          attributes: ['id', 'name', 'slug']
        }
      ],
      limit: parseInt(limit),
      offset: parseInt(offset),
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: {
        posts: rows,
        pagination: {
          total: count,
          page: parseInt(page),
          limit: parseInt(limit),
          totalPages: Math.ceil(count / limit)
        }
      }
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};