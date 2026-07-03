const fs = require('fs');
const https = require('https');
const path = require('path');

const dir = path.join(__dirname, 'php-app', 'assets', 'images');
if (!fs.existsSync(dir)){
    fs.mkdirSync(dir, { recursive: true });
}

const images = [
  { url: 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=600&h=400&fit=crop&auto=format', file: 'scholarships.jpg' },
  { url: 'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=600&h=400&fit=crop&auto=format', file: 'coaching.jpg' },
  { url: 'https://images.unsplash.com/photo-1542323228-002ac256e7b8?w=600&h=400&fit=crop&auto=format', file: 'mentorship.jpg' },
  { url: 'https://images.unsplash.com/photo-1694286066866-4324f80d7906?w=600&h=400&fit=crop&auto=format', file: 'community.jpg' },
  { url: 'https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=600&h=400&fit=crop&auto=format', file: 'blog1.jpg' },
  { url: 'https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=600&h=400&fit=crop&auto=format', file: 'blog2.jpg' },
  { url: 'https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=600&h=400&fit=crop&auto=format', file: 'blog3.jpg' },
  { url: 'https://images.unsplash.com/flagged/photo-1574097656146-0b43b7660cb6?w=1600&h=900&fit=crop&auto=format', file: 'hero-bg.jpg' },
  { url: 'https://images.unsplash.com/photo-1542323228-002ac256e7b8?w=1600&h=600&fit=crop&auto=format', file: 'cta-bg.jpg' },
  { url: 'https://images.unsplash.com/photo-1652648265326-73317a42c43d?w=700&h=900&fit=crop&auto=format', file: 'about-img.jpg' },
  { url: 'https://images.unsplash.com/photo-1761666520005-3ffcf13e74c8?w=700&h=700&fit=crop&auto=format', file: 'whyus-img.jpg' }
];

async function download(url, dest) {
  return new Promise((resolve, reject) => {
    const file = fs.createWriteStream(dest);
    https.get(url, function(response) {
      response.pipe(file);
      file.on('finish', function() {
        file.close(resolve); 
      });
    }).on('error', function(err) {
      fs.unlink(dest);
      reject(err);
    });
  });
}

async function run() {
  for (let img of images) {
    console.log(`Downloading ${img.file}...`);
    await download(img.url, path.join(dir, img.file));
  }
  console.log('Done!');
}

run();
