const mongoose = require('mongoose');

const blogPostSchema = new mongoose.Schema(
  {
    title: {
      type: String,
      required: [true, 'Please add a title'],
      unique: true,
      trim: true,
    },
    slug: {
      type: String,
      unique: true,
      lowercase: true,
    },
    summary: {
      type: String,
      required: [true, 'Please add a summary'],
      trim: true,
    },
    content: {
      type: String,
      required: [true, 'Please add post content'],
    },
    author: {
      type: mongoose.Schema.Types.ObjectId,
      ref: 'User',
      required: true,
    },
    coverImage: {
      type: String,
      default: '',
    },
    tags: [
      {
        type: String,
        trim: true,
      },
    ],
    status: {
      type: String,
      enum: ['draft', 'published'],
      default: 'draft',
    },
  },
  {
    timestamps: true,
  }
);

// Create slug from title before saving
blogPostSchema.pre('save', function (next) {
  if (!this.isModified('title')) {
    return next();
  }
  this.slug = this.title
    .toLowerCase()
    .replace(/[^\w\s-]/g, '') // remove special characters
    .replace(/\s+/g, '-')     // replace spaces with -
    .replace(/-+/g, '-');     // remove duplicate -
  next();
});

module.exports = mongoose.model('BlogPost', blogPostSchema);
