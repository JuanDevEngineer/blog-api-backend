<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\Blog\CreateBlogUseCase;
use App\Application\UseCases\Blog\DeleteBlogUseCase;
use App\Application\UseCases\Blog\FindAllBlogsUseCase;
use App\Application\UseCases\Blog\FindBlogByIdUseCase;
use App\Application\UseCases\Blog\UpdateBlogUseCase;
use App\Domain\Entities\Blog;
use App\Domain\Exceptions\BlogNotFoundException;
use App\Domain\Exceptions\CategoryNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Infrastructure\Storage\LocalFileStorage;

class BlogController extends AppController
{
    public function __construct(
        private readonly CreateBlogUseCase   $createBlog,
        private readonly FindAllBlogsUseCase $findAllBlogs,
        private readonly FindBlogByIdUseCase $findBlogById,
        private readonly UpdateBlogUseCase   $updateBlog,
        private readonly DeleteBlogUseCase   $deleteBlog,
        private readonly LocalFileStorage    $fileStorage,
    ) {}

    public function findAll(): void
    {
        echo $this->methodOk('', $this->findAllBlogs->execute());
    }

    public function findById(int $id): void
    {
        try {
            $blog = $this->findBlogById->execute($id);
            echo $this->methodOk('', $this->blogToArray($blog));
        } catch (BlogNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        }
    }

    public function create(): void
    {
        try {
            $imagePath = '';
            if ($this->fileStorage->hasFile($_FILES)) {
                $imagePath = $this->fileStorage->store($_FILES['blog']);
            }

            $data = $this->isJson() ? $this->request() : $_POST;

            $blog = new Blog(
                id:         null,
                categoryId: (int) ($data['category_id'] ?? 0),
                title:      $data['title']      ?? '',
                slug:       $data['slug']       ?? '',
                textShort:  $data['text_short'] ?? '',
                textLarge:  $data['text_large'] ?? '',
                pathImage:  $imagePath,
            );

            $this->createBlog->execute($blog);
            echo $this->methodCreated('Blog created successfully');
        } catch (ValidationException | CategoryNotFoundException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function update(int $id): void
    {
        $data = $this->request();

        try {
            $blog = new Blog(
                id:         $id,
                categoryId: (int) ($data['category_id'] ?? 0),
                title:      $data['title']      ?? '',
                slug:       $data['slug']       ?? '',
                textShort:  $data['text_short'] ?? '',
                textLarge:  $data['text_large'] ?? '',
            );

            $this->updateBlog->execute($blog);
            echo $this->methodOk('Blog updated successfully');
        } catch (BlogNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (ValidationException | CategoryNotFoundException $e) {
            echo $this->methodBadRequest($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->deleteBlog->execute($id);
            echo $this->methodOk('Blog deleted successfully');
        } catch (BlogNotFoundException $e) {
            echo $this->methodNotFound($e->getMessage());
        } catch (\Throwable) {
            echo $this->methodErrorServer('An unexpected error occurred');
        }
    }

    private function blogToArray(Blog $blog): array
    {
        return [
            'id'          => $blog->id,
            'category_id' => $blog->categoryId,
            'title'       => $blog->title,
            'slug'        => $blog->slug,
            'text_short'  => $blog->textShort,
            'text_large'  => $blog->textLarge,
            'path_image'  => $blog->pathImage,
            'created_at'  => $blog->createdAt,
            'updated_at'  => $blog->updatedAt,
        ];
    }
}
