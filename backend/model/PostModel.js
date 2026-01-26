import { Sequelize } from 'sequelize';
import db from '../config/Database.js';
import User from './UserModel.js';
import Category from './CategoryModel.js';

const { DataTypes } = Sequelize;

const Post = db.define('posts', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  userId: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'users',
      key: 'id'
    }
  },
  categoryId: {
    type: DataTypes.INTEGER,
    allowNull: true,
    references: {
      model: 'categories',
      key: 'id'
    }
  },
  title: {
    type: DataTypes.STRING(200),
    allowNull: false
  },
  description: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  imageUrl: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  tags: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  likes: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  views: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  }
}, {
  timestamps: true,
  freezeTableName: true
});

// Relationships
Post.belongsTo(User, { foreignKey: 'userId', as: 'user' });
Post.belongsTo(Category, { foreignKey: 'categoryId', as: 'category' });
User.hasMany(Post, { foreignKey: 'userId', as: 'posts' });
Category.hasMany(Post, { foreignKey: 'categoryId', as: 'posts' });

export default Post;