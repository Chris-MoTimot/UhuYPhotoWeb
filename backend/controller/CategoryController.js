import Category from '../model/CategoryModel.js';

export const getAllCategories = async (req, res) => {
  try {
    const categories = await Category.findAll({
      order: [['name', 'ASC']]
    });

    res.status(200).json({
      success: true,
      data: categories
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const getCategoryById = async (req, res) => {
  try {
    const { id } = req.params;

    const category = await Category.findByPk(id);

    if (!category) {
      return res.status(404).json({ 
        success: false,
        message: 'Kategori tidak ditemukan' 
      });
    }

    res.status(200).json({
      success: true,
      data: category
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const createCategory = async (req, res) => {
  try {
    const { name, description, icon } = req.body;

    if (!name) {
      return res.status(400).json({ 
        success: false,
        message: 'Nama kategori wajib diisi' 
      });
    }

    // Generate slug
    const slug = name.toLowerCase().replace(/\s+/g, '-');

    const category = await Category.create({
      name,
      slug,
      description: description || '',
      icon: icon || ''
    });

    res.status(201).json({
      success: true,
      message: 'Kategori berhasil dibuat',
      data: category
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const updateCategory = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, description, icon } = req.body;

    const category = await Category.findByPk(id);

    if (!category) {
      return res.status(404).json({ 
        success: false,
        message: 'Kategori tidak ditemukan' 
      });
    }

    let slug = category.slug;
    if (name && name !== category.name) {
      slug = name.toLowerCase().replace(/\s+/g, '-');
    }

    await category.update({
      name: name || category.name,
      slug,
      description: description || category.description,
      icon: icon || category.icon
    });

    res.status(200).json({
      success: true,
      message: 'Kategori berhasil diupdate',
      data: category
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};

export const deleteCategory = async (req, res) => {
  try {
    const { id } = req.params;

    const category = await Category.findByPk(id);

    if (!category) {
      return res.status(404).json({ 
        success: false,
        message: 'Kategori tidak ditemukan' 
      });
    }

    await category.destroy();

    res.status(200).json({
      success: true,
      message: 'Kategori berhasil dihapus'
    });
  } catch (error) {
    res.status(500).json({ 
      success: false,
      message: error.message 
    });
  }
};