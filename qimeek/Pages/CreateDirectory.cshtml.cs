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
    public class CreateDirectoryModel : PageModel
    {
        private readonly ILogger<DirectoriesModel> _logger;
        private readonly QimeekDbContext _dbContext;
        private readonly QimeekConfig _config;

        public CreateDirectoryModel(ILogger<DirectoriesModel> logger, QimeekDbContext dbContext, QimeekConfig config)
        {
            _logger = logger;
            _dbContext = dbContext;
            _config = config;
        }

        public Directory CurrentDirectory { get; set; }

        [BindProperty]
        public Directory NewDirectory { get; set; }

        public void OnGet(int id)
        {
            CurrentDirectory = new Directory { Id = id };
        }

        public IActionResult OnPost()
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            NewDirectory.UserId = userId;
            NewDirectory.DateAdded = DateTime.Now;

            _dbContext.Directories.Add(NewDirectory);
            _dbContext.SaveChanges();

            return Redirect("~/Directories?id=" + NewDirectory.ParentId);
        }
    }
}
