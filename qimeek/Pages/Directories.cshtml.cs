using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using qimeek.Data;
using qimeek.Models;

namespace qimeek.Pages
{
    [Authorize]
    public class DirectoriesModel : PageModel
    {
        private readonly ILogger<DirectoriesModel> _logger;
        private readonly QimeekDbContext _dbContext;
        private readonly QimeekConfig _config;

        public DirectoriesModel(ILogger<DirectoriesModel> logger, QimeekDbContext dbContext, QimeekConfig config)
        {
            _logger = logger;
            _dbContext = dbContext;
            _config = config;
        }

        public QimeekConfig Config
        {
            get { return _config; }
        }

        public Directory CurrentDirectory { get; set; }
        public List<Directory> ParentDirectories { get; set; }
        public List<Directory> SubDirectories { get; set; }
        public string Query { get; set; }
        public string ThumbnailProviderLink { get; set; }

        public void OnGet(int id, string query)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            // get all directories of the user, not only the subdirectories of the current directory because we need all dirs to build the breadcrumb (without making multiple db trips)
            List<Directory> directories = _dbContext.Directories.Where(d => d.UserId == userId).Select(x => new Directory { Id = x.Id, Name = x.Name, ParentId = x.ParentId, Bookmarks = x.Bookmarks }).ToList();

            if (string.IsNullOrEmpty(query))
            {
                CurrentDirectory = directories.Where(d => d.Id == id).First();
                SubDirectories = directories.Where(d => d.ParentId == CurrentDirectory.Id).ToList();

                // find parent directories for the breadcrumb
                Directory parentDir = CurrentDirectory;
                ParentDirectories = new List<Directory>();
                while (parentDir.ParentId != null)
                {
                    parentDir = directories.Where(d => d.Id == parentDir.ParentId).First();
                    ParentDirectories.Add(parentDir);
                }
                // put parentDirs in the right order
                ParentDirectories.Reverse();
            }
            else
            {
                CurrentDirectory = new Directory() { Name = "Search" };

                foreach (Directory directory in directories)
                {
                    var matches = directory.Bookmarks.Where(b => b.Title.ToLower().Contains(query.ToLower())).ToList();

                    foreach (Bookmark bookmark in matches)
                    {
                        CurrentDirectory.Bookmarks.Add(bookmark);
                    }
                }
                
                SubDirectories = directories.Where(d => d.Name.Contains(query)).ToList();
            }

            Uri uri = new Uri(Config.ThumbnailProviderUrl);
            ThumbnailProviderLink = $"{uri.Scheme}://{uri.Host}";
        }

        public async Task<IActionResult> OnPostDeleteAsync(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Directory directoryToDelete = _dbContext.Directories.Where(d => d.Id == id && d.UserId == userId).First();

            if (directoryToDelete != null)
            {
                if (directoryToDelete.ParentId != null) // don't delete first level directory
                {
                    _dbContext.Directories.Remove(directoryToDelete);
                    await _dbContext.SaveChangesAsync();
                }
            }

            return Redirect("~/Directories?id=" + directoryToDelete.ParentId);
        }
    }
}
