using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.Extensions.Logging;
using qimeek.Data;

namespace qimeek.Pages
{
    public class EditBookmarkModel : PageModel
    {
        private readonly ILogger<DirectoriesModel> _logger;
        private readonly QimeekDbContext _dbContext;
        private readonly QimeekConfig _config;

        public EditBookmarkModel(ILogger<DirectoriesModel> logger, QimeekDbContext dbContext, QimeekConfig config)
        {
            _logger = logger;
            _dbContext = dbContext;
            _config = config;
        }

        [BindProperty]
        public Bookmark Bookmark { get; set; }

        public void OnGet(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Bookmark = _dbContext.Bookmarks.Where(d => d.Id == id && d.UserId == userId).First();
        }

        public async Task<IActionResult> OnPostAsync(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Bookmark bookmarkToUpdate = _dbContext.Bookmarks.Where(d => d.Id == id && d.UserId == userId).First();

            if (bookmarkToUpdate != null)
            {
                if (await TryUpdateModelAsync<Bookmark>(
                    bookmarkToUpdate,
                    "bookmark",
                    b => b.Url, b => b.Title))
                {
                    _dbContext.SaveChanges();
                }
            }

            return Redirect("~/Directories?id=" + Bookmark.DirectoryId);
        }

        public async Task<IActionResult> OnPostDeleteAsync(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Bookmark bookmarkToDelete = _dbContext.Bookmarks.Where(d => d.Id == id && d.UserId == userId).First();

            if (bookmarkToDelete != null)
            {
                _dbContext.Bookmarks.Remove(bookmarkToDelete);
                await _dbContext.SaveChangesAsync();
            }

            return Redirect("~/Directories?id=" + bookmarkToDelete.DirectoryId);
        }
    }
}
