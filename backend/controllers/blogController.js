const BlogPost = require('../models/BlogPost');

// @desc    Get all published blog posts
// @route   GET /api/blogs
// @access  Public
const getBlogPosts = async (req, res, next) => {
  try {
    const posts = await BlogPost.find({ status: 'published' })
      .populate('author', 'name email')
      .sort({ createdAt: -1 });

    res.json({
      success: true,
      count: posts.length,
      data: posts,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get all blog posts (drafts & published) for admin/author
// @route   GET /api/blogs/admin
// @access  Private (Admin/Author)
const getAdminBlogPosts = async (req, res, next) => {
  try {
    let query = {};
    
    // If user is author (not admin), show only their own posts
    if (req.user.role === 'author') {
      query = { author: req.user._id };
    }

    const posts = await BlogPost.find(query)
      .populate('author', 'name email')
      .sort({ createdAt: -1 });

    res.json({
      success: true,
      count: posts.length,
      data: posts,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Get single blog post by slug
// @route   GET /api/blogs/:slug
// @access  Public
const getBlogPostBySlug = async (req, res, next) => {
  try {
    const post = await BlogPost.findOne({ slug: req.params.slug, status: 'published' })
      .populate('author', 'name email');

    if (!post) {
      res.status(404);
      throw new Error('Blog post not found or is in draft');
    }

    res.json({
      success: true,
      data: post,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Create a blog post
// @route   POST /api/blogs
// @access  Private (Admin/Author)
const createBlogPost = async (req, res, next) => {
  try {
    const { title, summary, content, coverImage, tags, status } = req.body;

    if (!title || !summary || !content) {
      res.status(400);
      throw new Error('Please enter all required fields: title, summary, and content');
    }

    const post = await BlogPost.create({
      title,
      summary,
      content,
      coverImage,
      tags,
      status,
      author: req.user._id,
    });

    res.status(201).json({
      success: true,
      message: 'Blog post created successfully',
      data: post,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Update a blog post
// @route   PUT /api/blogs/:id
// @access  Private (Admin/Author)
const updateBlogPost = async (req, res, next) => {
  try {
    const post = await BlogPost.findById(req.params.id);

    if (!post) {
      res.status(404);
      throw new Error('Blog post not found');
    }

    // Check post ownership (Only author of the post or admin can edit)
    if (post.author.toString() !== req.user._id.toString() && req.user.role !== 'admin') {
      res.status(403);
      throw new Error('Not authorized to update this blog post');
    }

    const { title, summary, content, coverImage, tags, status } = req.body;

    post.title = title !== undefined ? title : post.title;
    post.summary = summary !== undefined ? summary : post.summary;
    post.content = content !== undefined ? content : post.content;
    post.coverImage = coverImage !== undefined ? coverImage : post.coverImage;
    post.tags = tags !== undefined ? tags : post.tags;
    post.status = status !== undefined ? status : post.status;

    const updatedPost = await post.save();

    res.json({
      success: true,
      message: 'Blog post updated successfully',
      data: updatedPost,
    });
  } catch (error) {
    next(error);
  }
};

// @desc    Delete a blog post
// @route   DELETE /api/blogs/:id
// @access  Private (Admin/Author)
const deleteBlogPost = async (req, res, next) => {
  try {
    const post = await BlogPost.findById(req.params.id);

    if (!post) {
      res.status(404);
      throw new Error('Blog post not found');
    }

    // Check post ownership
    if (post.author.toString() !== req.user._id.toString() && req.user.role !== 'admin') {
      res.status(403);
      throw new Error('Not authorized to delete this blog post');
    }

    await post.deleteOne();

    res.json({
      success: true,
      message: 'Blog post removed successfully',
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getBlogPosts,
  getAdminBlogPosts,
  getBlogPostBySlug,
  createBlogPost,
  updateBlogPost,
  deleteBlogPost,
};
