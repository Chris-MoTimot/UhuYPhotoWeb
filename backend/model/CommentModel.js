import { Sequelize } from 'sequelize';
import db from '../config/Database.js';
import User from './UserModel.js';
import Post from './PostModel.js';

const { DataTypes } = Sequelize;

const Comment = db.define('comments', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  postId: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'posts',
      key: 'id'
    }
  },
  userId: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'users',
      key: 'id'
    }
  },
  content: {
    type: DataTypes.TEXT,
    allowNull: false
  }
}, {
  timestamps: true,
  freezeTableName: true
});

// Relationships
Comment.belongsTo(User, { foreignKey: 'userId', as: 'user' });
Comment.belongsTo(Post, { foreignKey: 'postId', as: 'post' });
Post.hasMany(Comment, { foreignKey: 'postId', as: 'comments' });
User.hasMany(Comment, { foreignKey: 'userId', as: 'comments' });

export default Comment;