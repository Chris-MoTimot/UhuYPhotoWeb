import Comment from '../model/CommentModel.js';
import User from '../model/UserModel.js';
import Post from '../model/PostModel.js';

export const getCommentsByPost = async (req, res) => {
  try {
    const { postId } = req.params;

    const comments = await Comment.findAll({
      where: { postId },
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        }
      ],
      order: [['createdAt', 'DESC']]
    });

    res.status(200).json({
      success: true,
      data: comments
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const createComment = async (req, res) => {
  try {
    const { postId } = req.params;
    const { content } = req.body;
    const userId = req.user.id;

    if (!content) {
      return res.status(400).json({ 
        success: false,
        message: 'Komentar tidak boleh kosong' 
      });
    }

    // Cek apakah post ada
    const post = await Post.findByPk(postId);
    if (!post) {
      return res.status(404).json({ 
        success: false,
        message: 'Post tidak ditemukan' 
      });
    }

    const comment = await Comment.create({
      postId,
      userId,
      content
    });

    const newComment = await Comment.findByPk(comment.id, {
      include: [
        {
          model: User,
          as: 'user',
          attributes: ['id', 'username', 'profileImage']
        }
      ]
    });

    res.status(201).json({
      success: true,
      message: 'Komentar berhasil ditambahkan',
      data: newComment
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const deleteComment = async (req, res) => {
  try {
    const { id } = req.params;
    const userId = req.user.id;

    const comment = await Comment.findByPk(id);

    if (!comment) {
      return res.status(404).json({ 
        success: false,
        message: 'Komentar tidak ditemukan' 
      });
    }

    // Cek apakah user adalah pemilik komentar
    if (comment.userId !== userId && req.user.role !== 'admin') {
      return res.status(403).json({ 
        success: false,
        message: 'Anda tidak memiliki akses untuk menghapus komentar ini' 
      });
    }

    await comment.destroy();

    res.status(200).json({
      success: true,
      message: 'Komentar berhasil dihapus'
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};