# QuickThumb - PHP Image Thumbnail Generator

QuickThumb is a PHP class that simplifies the process of generating image thumbnails from existing image files. It offers a convenient way to create resized versions of images while maintaining their aspect ratios.

## Features

- Generates image thumbnails with specified dimensions.
- Supports common image formats: JPEG, PNG, GIF.
- Automatically calculates thumbnail dimensions based on aspect ratio.
- Simple and easy-to-use API.

## Installation

You can include the QuickThumb class in your PHP project using composer or by manually including it in your project.

### Composer Installation

To install QuickThumb via Composer, run the following command in your project directory:

```bash
composer require marianojwl/quickthumb
```

### Manual Installation

1. Download the [QuickThumb.php](https://github.com/yourusername/yourrepository/blob/main/QuickThumb.php) file from this repository.
2. Place the `QuickThumb.php` file in your project directory.

## Usage

Here's how you can use QuickThumb to generate a thumbnail from an image:

```php
<?php
require_once 'QuickThumb.php'; // Include the QuickThumb class

use marianojwl\QuickThumb\QuickThumb;

// Path to the original image
$originalImagePath = '/path/to/your/original/image.jpg';

// Generate the thumbnail using the QuickThumb class
$thumbnailPath = QuickThumb::make($originalImagePath);

if ($thumbnailPath) {
    echo "Thumbnail created successfully. Path: $thumbnailPath";
} else {
    echo "Thumbnail creation failed.";
}
?>
```

Replace `'/path/to/your/original/image.jpg'` with the actual path to the image from which you want to generate a thumbnail.

## Configuration

You can configure the following aspects of thumbnail generation:

- Thumbnail dimensions (width and height) - Specify the desired width and height for the thumbnail.
- Supported image formats - QuickThumb supports JPEG, PNG, and GIF formats by default. You can extend this support by modifying the `imageCreate()` and `imageSave()` methods in the `QuickThumb` class.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

- [Mariano Soto](https://github.com/marianojwl)

## Contributions

Contributions to this project are welcome. Feel free to open issues and pull requests.

```

Please make sure to replace the placeholders like `[Your Name]`, `[Your GitHub Username]`, and `[Your Repository URL]` with your actual information. You should also update the installation instructions to reflect how users can install your QuickThumb class in their projects.