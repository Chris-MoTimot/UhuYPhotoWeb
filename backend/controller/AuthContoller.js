import User from '../model/UserModel.js';
import bcrypt from 'bcrypt';
import jwt from 'jsonwebtoken';

const JWT_SECRET = process.env.JWT_SECRET || 'your-secret-key-change-in-production';

export const register = async (req, res) => {
  try {
    const { username, email, password, fullName } = req.body;

    // Validasi input
    if (!username || !email || !password) {
      return res.status(400).json({ 
        success: false,
        message: 'Username, email, dan password wajib diisi' 
      });
    }

    // Cek apakah user sudah ada
    const existingUser = await User.findOne({ 
      where: { 
        [db.Sequelize.Op.or]: [{ email }, { username }] 
      } 
    });

    if (existingUser) {
      return res.status(400).json({ 
        success: false,
        message: 'Email atau username sudah digunakan' 
      });
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    // Buat user baru
    const user = await User.create({
      username,
      email,
      password: hashedPassword,
      fullName: fullName || username
    });

    // Generate token
    const token = jwt.sign(
      { id: user.id, email: user.email, role: user.role },
      JWT_SECRET,
      { expiresIn: '7d' }
    );

    res.status(201).json({
      success: true,
      message: 'Registrasi berhasil',
      data: {
        token,
        user: {
          id: user.id,
          username: user.username,
          email: user.email,
          fullName: user.fullName,
          profileImage: user.profileImage
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

export const login = async (req, res) => {
  try {
    const { email, password } = req.body;

    // Validasi input
    if (!email || !password) {
      return res.status(400).json({ 
        success: false,
        message: 'Email dan password wajib diisi' 
      });
    }

    // Cari user
    const user = await User.findOne({ where: { email } });

    if (!user) {
      return res.status(401).json({ 
        success: false,
        message: 'Email atau password salah' 
      });
    }

    // Cek password
    const isValidPassword = await bcrypt.compare(password, user.password);

    if (!isValidPassword) {
      return res.status(401).json({ 
        success: false,
        message: 'Email atau password salah' 
      });
    }

    // Generate token
    const token = jwt.sign(
      { id: user.id, email: user.email, role: user.role },
      JWT_SECRET,
      { expiresIn: '7d' }
    );

    res.status(200).json({
      success: true,
      message: 'Login berhasil',
      data: {
        token,
        user: {
          id: user.id,
          username: user.username,
          email: user.email,
          fullName: user.fullName,
          profileImage: user.profileImage,
          bio: user.bio
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

export const getProfile = async (req, res) => {
  try {
    const user = await User.findByPk(req.user.id, {
      attributes: { exclude: ['password'] }
    });

    if (!user) {
      return res.status(404).json({ 
        success: false,
        message: 'User tidak ditemukan' 
      });
    }

    res.status(200).json({
      success: true,
      data: user
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const updateProfile = async (req, res) => {
  try {
    const { fullName, bio, username } = req.body;
    const userId = req.user.id;

    const user = await User.findByPk(userId);

    if (!user) {
      return res.status(404).json({ 
        success: false,
        message: 'User tidak ditemukan' 
      });
    }

    // Update data
    await user.update({
      fullName: fullName || user.fullName,
      bio: bio || user.bio,
      username: username || user.username
    });

    res.status(200).json({
      success: true,
      message: 'Profile berhasil diupdate',
      data: {
        id: user.id,
        username: user.username,
        email: user.email,
        fullName: user.fullName,
        bio: user.bio,
        profileImage: user.profileImage
      }
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};