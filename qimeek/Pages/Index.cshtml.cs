using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.Extensions.Logging;
using qimeek.Data;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;

namespace qimeek.Pages
{
    [Authorize]
    public class IndexModel : PageModel
    {
        private readonly ILogger<IndexModel> _logger;
        private readonly QimeekDbContext _dbContext;
        private readonly QimeekConfig _config;

        public IndexModel(ILogger<IndexModel> logger, QimeekDbContext dbContext, QimeekConfig config)
        {
            _logger = logger;
            _dbContext = dbContext;
            _config = config;
        }

        public List<Bookmark> Bookmarks { get; set; }
        public string ThumbnailProviderLink { get; set; }

        public QimeekConfig Config
        {
            get { return _config; }
        }

        public void OnGet()
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);
            Bookmarks = _dbContext.Bookmarks.Where(b => b.UserId == userId).Take(5).ToList();

            Uri uri = new Uri(Config.ThumbnailProviderUrl);
            ThumbnailProviderLink = $"{uri.Scheme}://{uri.Host}";
        }
    }
}
