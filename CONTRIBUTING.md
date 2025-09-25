# Contributing to Apipedia PHP SDK

We love your input! We want to make contributing to the Apipedia PHP SDK as easy and transparent as possible, whether it's:

- Reporting a bug
- Discussing the current state of the code
- Submitting a fix
- Proposing new features
- Becoming a maintainer

## Development Process

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.

### Pull Requests Process

1. Fork the repo and create your branch from `main`.
2. If you've added code that should be tested, add tests.
3. If you've changed APIs, update the documentation.
4. Ensure the test suite passes.
5. Make sure your code lints.
6. Issue that pull request!

## Development Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/apipedia/php-sdk.git
   cd php-sdk
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Run tests**
   ```bash
   composer test
   ```

4. **Check code style**
   ```bash
   composer phpcs
   ```

5. **Fix code style**
   ```bash
   composer phpcs-fix
   ```

6. **Run static analysis**
   ```bash
   composer phpstan
   ```

## Code Style

We follow the PSR-12 coding standard. Please ensure your code adheres to this standard:

- Use 4 spaces for indentation (no tabs)
- Line length should not exceed 120 characters
- Use meaningful variable and function names
- Add appropriate docblocks for all public methods
- Follow PSR-4 autoloading standards

### Example Code Style

```php
<?php

namespace Apipedia;

use GuzzleHttp\Client;
use InvalidArgumentException;

/**
 * Example class following our coding standards
 */
class Example
{
    private string $property;

    /**
     * Constructor with proper docblock
     *
     * @param string $property The property value
     * @throws InvalidArgumentException When property is empty
     */
    public function __construct(string $property)
    {
        if (empty($property)) {
            throw new InvalidArgumentException('Property cannot be empty');
        }

        $this->property = $property;
    }

    /**
     * Example method with proper formatting
     *
     * @param array $data Input data
     * @return array Processed data
     */
    public function processData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            $result[$key] = $this->processValue($value);
        }

        return $result;
    }
}
```

## Testing

We use PHPUnit for testing. All new features should include comprehensive tests.

### Test Structure

- Unit tests go in the `tests/` directory
- Test classes should end with `Test` (e.g., `ApipediaTest.php`)
- Use descriptive test method names that explain what is being tested
- Mock external dependencies using PHPUnit's mocking capabilities

### Example Test

```php
<?php

namespace Apipedia\Tests;

use Apipedia\Apipedia;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testExampleFeature(): void
    {
        // Arrange
        $apipedia = new Apipedia('test_key', 'test_auth');

        // Act
        $result = $apipedia->someMethod('test_input');

        // Assert
        $this->assertInstanceOf(Apipedia::class, $result);
    }
}
```

## Documentation

Please update documentation when making changes:

- Update the `README.md` for user-facing changes
- Update `docs/API.md` for API changes
- Add or update examples in the `examples/` directory
- Update the `CHANGELOG.md` with your changes

### Documentation Style

- Use clear, concise language
- Include code examples for new features
- Use proper Markdown formatting
- Keep examples up-to-date and working

## Commit Messages

Write clear commit messages that explain what and why:

```
Add WhatsApp media upload support

- Add support for local file uploads
- Implement file validation
- Add tests for media functionality
- Update documentation with examples
```

### Commit Message Format

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit first line to 50 characters
- Reference issues and pull requests when applicable
- Include breaking changes in the commit body

## Bug Reports

We use GitHub issues to track public bugs. Report a bug by opening a new issue.

**Great Bug Reports** tend to have:

- A quick summary and/or background
- Steps to reproduce
  - Be specific!
  - Give sample code if you can
- What you expected would happen
- What actually happens
- Notes (possibly including why you think this might be happening, or stuff you tried that didn't work)

### Bug Report Template

```markdown
**Describe the bug**
A clear and concise description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Initialize SDK with '...'
2. Call method '...' with parameters '...'
3. See error

**Expected behavior**
A clear and concise description of what you expected to happen.

**Code Sample**
```php
// Minimal code sample that reproduces the issue
$apipedia = new Apipedia('key', 'auth');
$result = $apipedia->problematicMethod();
```

**Environment:**
- PHP Version: [e.g. 8.1]
- SDK Version: [e.g. 1.0.0]
- OS: [e.g. Ubuntu 20.04]

**Additional context**
Add any other context about the problem here.
```

## Feature Requests

Feature requests are welcome! Please provide:

- **Clear description** of the feature
- **Use case** - why would this be useful?
- **Proposed API** - how should it work?
- **Backwards compatibility** considerations

## Code Review Process

All submissions require review. We use GitHub pull requests for this purpose.

### Review Criteria

- Code quality and style compliance
- Test coverage
- Documentation updates
- Backwards compatibility
- Performance impact
- Security considerations

## Release Process

1. Update version numbers in `composer.json`
2. Update `CHANGELOG.md` with new version
3. Create a new release on GitHub
4. Tag the release with semantic version
5. Publish to Packagist (automatic via webhook)

## Community

- Be respectful and constructive
- Help others in issues and discussions
- Share knowledge and best practices
- Follow our [Code of Conduct](CODE_OF_CONDUCT.md)

## Getting Help

- Check existing [issues](https://github.com/apipedia/php-sdk/issues)
- Read the [documentation](README.md)
- Look at [examples](examples/)
- Ask questions in [discussions](https://github.com/apipedia/php-sdk/discussions)

## License

By contributing, you agree that your contributions will be licensed under the same license as the project (MIT License).

## Recognition

Contributors will be recognized in:
- README.md contributors section
- Release notes
- GitHub contributors page

Thank you for contributing to the Apipedia PHP SDK! 🎉