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

        public Directory CurrentDirectory { get; set; }
        public List<Directory> ParentDirectories { get; set; }
        public List<Directory> SubDirectories { get; set; }
        public List<Bookmark> Bookmarks { get; set; }
        public string ThumbnailProviderLink { get; set; }

        public void OnGet(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            // get all directories of the user, not only the subdirectories of the current directory because we need all dirs to build the breadcrumb (without making multiple db trips)
            List<Directory> directories = _dbContext.Directories.Where(d => d.UserId == userId).Select(x => new Directory { Id = x.Id, Name = x.Name, ParentId = x.ParentId }).ToList();

            CurrentDirectory = directories.Where(d => d.Id == id).First();
            SubDirectories = directories.Where(d => d.ParentId == CurrentDirectory.Id).ToList();

            // find parent directories for the breadcrumb
            Directory parentDir = CurrentDirectory;
            ParentDirectories = new List<Directory>();
            while(parentDir.ParentId != null)
            {
                parentDir = directories.Where(d => d.Id == parentDir.ParentId).First();
                ParentDirectories.Add(parentDir);
            }
            // put parentDirs in the right order
            ParentDirectories.Reverse();

            Bookmarks = _dbContext.Bookmarks.Where(b => b.DirectoryId == CurrentDirectory.Id && b.UserId == userId).Select(x => new Bookmark { Title = x.Title, Url = x.Url }).ToList();

            Uri uri = new Uri(Config.ThumbnailProviderUrl);
            ThumbnailProviderLink = $"{uri.Scheme}://{uri.Host}";
        }
    }
}
