using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.Extensions.Logging;
using qimeek.Data;

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

        public List<Directory> SubDirectories { get; set; }
        public List<Bookmark> Bookmarks { get; set; }
        public string ThumbnailProviderLink { get; set; }

        public void OnGet(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Directory currentDirectory;
            if (id == 0)
            {
                currentDirectory = _dbContext.Directories.Where(d => d.ParentId == null && d.UserId == userId).Select(x => new Directory { Id = x.Id }).First();
            }
            else
            {
                currentDirectory = _dbContext.Directories.Where(d => d.Id == id && d.UserId == userId).Select(x => new Directory { Id = x.Id }).First();
            }

            SubDirectories = _dbContext.Directories.Where(d => d.ParentId == currentDirectory.Id && d.UserId == userId).Select(x => new Directory { Id = x.Id, Name = x.Name }).OrderBy(o => o.Name).ToList();
            
            Bookmarks = _dbContext.Bookmarks.Where(b => b.DirectoryId == currentDirectory.Id && b.UserId == userId).Select(x => new Bookmark { Title = x.Title, Url = x.Url }).ToList();

            Uri uri = new Uri(Config.ThumbnailProviderUrl);
            ThumbnailProviderLink = $"{uri.Scheme}://{uri.Host}";
        }
    }
}
